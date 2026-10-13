<?php

/**
 * Writes the Markdown report of a benchmark run from the samples of bin/measure.php and the throughput of ab.
 *
 * Usage: php bin/report.php <prefix of the result files> <environment> <rounds> <runs> <warmup> <requests>
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

[, $prefix, $environment, $rounds, $runs, $warmup, $requests] = bench_arguments() + array_fill(0, 7, '');

/**
 * The samples of all rounds of a theme by scenario: median and p95 of each timing.
 *
 * @return array<string, array<string, array{median: float, p95: float}>>
 */
function bench_results(string $prefix, string $theme): array
{
    $samples = [];
    foreach (glob(sprintf('%s-%s-round*.json', $prefix, $theme)) ?: [] as $file) {
        /** @var array<string, list<array<string, float>>> $round Written by bin/measure.php. */
        $round = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($round as $name => $list) {
            $samples[$name] ??= [];
            array_push($samples[$name], ...$list);
        }
    }
    $results = [];
    foreach ($samples as $name => $list) {
        foreach (array_keys($list[0] ?? []) as $key) {
            $values = array_map(floatval(...), array_column($list, $key));
            $results[$name][$key] = ['median' => bench_percentile($values, 0.5), 'p95' => bench_percentile($values, 0.95)];
        }
    }

    return $results;
}

/**
 * @param list<float> $values
 */
function bench_percentile(array $values, float $percentile): float
{
    sort($values);
    $index = (int) ceil($percentile * count($values)) - 1;

    return $values[max(0, min(count($values) - 1, $index))] ?? 0.0;
}

/**
 * @return array<string, string> Requests per second by page.
 */
function bench_throughput(string $file): array
{
    $throughput = [];
    foreach (is_file($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] : [] as $line) {
        [$page, $rps] = explode(' ', $line) + [1 => ''];
        $throughput[$page] = $rps;
    }

    return $throughput;
}

/**
 * @param array<string, array{median: float, p95: float}> $summary
 * @param 'median'|'p95' $statistic
 */
function bench_value(array $summary, string $key, string $statistic = 'median'): float
{
    return $summary[$key][$statistic] ?? 0.0;
}

function bench_ms(float $value): string
{
    return number_format($value, 1, '.', '');
}

function bench_mb(float $bytes): string
{
    return number_format($bytes / 1048576, 1, '.', '');
}

/**
 * A row of the cost table: the difference of two scenarios.
 *
 * @param array<string, array{median: float, p95: float}>|null $with
 * @param array<string, array{median: float, p95: float}>|null $without
 */
function bench_difference(string $label, ?array $with, ?array $without): string
{
    if ($with === null || $without === null) {
        return '';
    }

    return sprintf(
        "| %s | %+.1f | %+.1f | %+.1f | %+.1f | %+.1f | %+d |\n",
        $label,
        bench_value($with, 'total') - bench_value($without, 'total'),
        bench_value($with, 'theme') - bench_value($without, 'theme'),
        bench_value($with, 'init') - bench_value($without, 'init'),
        bench_value($with, 'main') - bench_value($without, 'main'),
        (bench_value($with, 'memoryPeak') - bench_value($without, 'memoryPeak')) / 1048576,
        (int) (bench_value($with, 'queries') - bench_value($without, 'queries')),
    );
}

$themes = ['theme-bench-baseline' => 'without framework', 'theme-bench' => 'with framework'];
$results = [];
foreach (array_keys($themes) as $theme) {
    $results[$theme] = bench_results($prefix, $theme);
}

echo "# Benchmark of wp-app-framework\n\n";
echo sprintf("%s, %s. %s rounds, each with both themes in turn; per round and scenario %s warm-up requests, then %s measured requests, the scenarios in turn, one request at a time. Times in milliseconds (median, p95 in parentheses), memory in MB.\n\n", date('Y-m-d H:i'), $environment, $rounds, $warmup, $runs);
echo "Phases: **core** up to `setup_theme` (WordPress and plugins), **theme** `functions.php` and `after_setup_theme` (the boot of the framework), **init** up to `wp_loaded` (registration of blocks, post types, routes), **main** the request itself (query and template, or the REST dispatch), **total** the request on the server, **client** as measured by the client, **queries** the database queries of the request.\n\n";

echo "| Scenario | Theme | core | theme | init | main | total | client | peak memory | queries |\n|---|---|---:|---:|---:|---:|---:|---:|---:|---:|\n";
$names = array_unique([...array_keys($results['theme-bench-baseline']), ...array_keys($results['theme-bench'])]);
foreach ($names as $name) {
    foreach ($themes as $theme => $label) {
        $result = $results[$theme][$name] ?? null;
        if ($result === null) {
            continue;
        }
        echo sprintf(
            "| %s | %s | %s | %s | %s | %s | %s (%s) | %s | %s | %d |\n",
            $name,
            $label,
            bench_ms(bench_value($result, 'core')),
            bench_ms(bench_value($result, 'theme')),
            bench_ms(bench_value($result, 'init')),
            bench_ms(bench_value($result, 'main')),
            bench_ms(bench_value($result, 'total')),
            bench_ms(bench_value($result, 'total', 'p95')),
            bench_ms(bench_value($result, 'wall')),
            bench_mb(bench_value($result, 'memoryPeak')),
            (int) bench_value($result, 'queries'),
        );
    }
}

$framework = $results['theme-bench'];
$baseline = $results['theme-bench-baseline'];
echo "\n## Cost of the framework\n\n| Comparison | total | theme | init | main | peak memory | queries |\n|---|---:|---:|---:|---:|---:|---:|\n";
echo bench_difference('Base load: empty page, framework theme vs. baseline', $framework['empty page'] ?? null, $baseline['empty page'] ?? null);
echo bench_difference('Base load: 100 core blocks, framework theme vs. baseline', $framework['100 core blocks'] ?? null, $baseline['100 core blocks'] ?? null);
echo bench_difference('REST: route without framework, framework theme vs. baseline', $framework['REST route without framework'] ?? null, $baseline['REST route without framework'] ?? null);
echo bench_difference('100 framework text blocks vs. 100 core blocks (framework theme)', $framework['100 framework text blocks'] ?? null, $framework['100 core blocks'] ?? null);
echo bench_difference('100 cached text blocks vs. 100 core blocks (framework theme)', $framework['100 cached text blocks'] ?? null, $framework['100 core blocks'] ?? null);
echo bench_difference('REST route of the framework vs. plain route (framework theme)', $framework['REST route of the framework'] ?? null, $framework['REST route without framework'] ?? null);

if ((int) $requests > 0) {
    echo sprintf("\n## Throughput\n\nRequests per second with `ab -n %s -c 4` against the front end (without the timer).\n\n| Page | without framework | with framework |\n|---|---:|---:|\n", $requests);
    $without = bench_throughput($prefix . '-theme-bench-baseline-ab.txt');
    $with = bench_throughput($prefix . '-theme-bench-ab.txt');
    foreach (array_unique([...array_keys($without), ...array_keys($with)]) as $page) {
        echo sprintf("| /%s/ | %s | %s |\n", $page, $without[$page] ?? '–', $with[$page] ?? '–');
    }
}
