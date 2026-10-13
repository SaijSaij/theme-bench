<?php

/**
 * Runs the scenarios of the benchmark against a site and prints the samples as JSON (scenario name => list of
 * timings). Runs inside the container of the site (php reads this script from stdin), so the requests do not cross
 * the Docker network.
 *
 * Usage: php -- <scenarios.json> < measure.php
 * The JSON holds {base, warmup, runs, auth?, scenarios: [{name, method, path, body?, auth?}]}.
 */

declare(strict_types=1);

/**
 * The command line arguments.
 *
 * @return list<string>
 */
function bench_arguments(): array
{
    $arguments = [];
    foreach ((array) ($_SERVER['argv'] ?? []) as $argument) {
        $arguments[] = is_scalar($argument) ? (string) $argument : '';
    }

    return $arguments;
}

$decoded = json_decode((string) file_get_contents(bench_arguments()[1] ?? 'php://stdin'), true, 512, JSON_THROW_ON_ERROR);
if (!is_array($decoded) || !is_string($decoded['base'] ?? null) || !is_array($decoded['scenarios'] ?? null)) {
    fwrite(STDERR, "ERROR: invalid scenario file.\n");
    exit(1);
}
/** @var array{base: string, warmup: int, runs: int, auth?: string, scenarios: list<array{name: string, path: string, method?: string, body?: mixed, auth?: bool, expect?: string}>} $config Written by bin/bench. */
$config = $decoded;

/**
 * One request; the timings from the comment the timer plugin appends, plus the wall time of the client.
 *
 * @param array{name: string, path: string, method?: string, body?: mixed, auth?: bool, expect?: string} $scenario
 * @return array<string, float>
 */
function bench_request(string $base, array $scenario, ?string $auth): array
{
    $method = $scenario['method'] ?? 'GET';
    $curl = curl_init($base . $scenario['path']);
    $headers = ['X-Bench: 1'];
    /** @var array<int, mixed> $options */
    $options = [CURLOPT_RETURNTRANSFER => true];
    if ($method === 'POST') {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = (string) json_encode($scenario['body'] ?? []);
        $headers[] = 'Content-Type: application/json';
    }
    if (($scenario['auth'] ?? false) && $auth !== null) {
        $options[CURLOPT_USERPWD] = $auth;
    }
    $options[CURLOPT_HTTPHEADER] = $headers;
    curl_setopt_array($curl, $options);
    $body = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $wall = curl_getinfo($curl, CURLINFO_TOTAL_TIME) * 1000;
    if (!is_string($body) || $status !== 200) {
        throw new RuntimeException(sprintf('%s %s answered HTTP %d.', $method, $scenario['path'], $status));
    }
    if (preg_match('/<!--bench-timing:(\{.*?\})-->/', $body, $match) !== 1) {
        throw new RuntimeException(sprintf('%s %s has no timings; is the plugin bench-timer active?', $method, $scenario['path']));
    }
    if (isset($scenario['expect']) && !str_contains($body, $scenario['expect'])) {
        throw new RuntimeException(sprintf('%s %s does not contain "%s".', $method, $scenario['path'], $scenario['expect']));
    }

    /** @var array<string, float> $timings Written by the plugin bench-timer. */
    $timings = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);

    return [...$timings, 'wall' => $wall];
}

// Warm-up per scenario, then the scenarios in turn, so a drift of the machine affects all of them alike.
foreach ($config['scenarios'] as $scenario) {
    for ($i = 0; $i < $config['warmup']; $i++) {
        bench_request($config['base'], $scenario, $config['auth'] ?? null);
    }
}
$samples = [];
for ($i = 0; $i < $config['runs']; $i++) {
    foreach ($config['scenarios'] as $scenario) {
        $samples[$scenario['name']][] = bench_request($config['base'], $scenario, $config['auth'] ?? null);
    }
}

echo json_encode($samples), "\n";
