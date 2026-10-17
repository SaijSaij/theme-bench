<?php

/**
 * Summarizes Xdebug profiles (cachegrind files): the time per component (framework, Twig, theme, Composer,
 * WordPress) and the functions of the framework and Twig with the largest inclusive and own time. The time of PHP's
 * internal functions counts for the function that calls them.
 *
 * Usage: php bin/profile-summary.php <cachegrind file>...
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

/**
 * @return string Component of a file of the release site.
 */
function bench_component(string $file): string
{
    return match (true) {
        // vendor-prefixed/ in release mode, vendor/ in development mode.
        preg_match('#/vendor(-prefixed)?/webcodingcat/wp-app-framework/#', $file) === 1 => 'framework',
        preg_match('#/vendor(-prefixed)?/twig/#', $file) === 1 || str_contains($file, '/cache/theme-bench/twig/') => 'Twig (with compiled templates)',
        preg_match('#/vendor(-prefixed)?/#', $file) === 1 => 'Composer and other dependencies',
        str_contains($file, '/themes/theme-bench/') => 'theme',
        str_contains($file, '/plugins/bench-timer/') => 'timer plugin',
        default => 'WordPress',
    };
}

/**
 * A compressed name: "fl=(1) /path" defines it, "fl=(1)" refers to it again.
 *
 * @param array<int|string, string> $names Names by number (PHP turns the numeric keys into integers).
 */
function bench_name(string $value, array &$names): string
{
    if (preg_match('/^\((\d+)\)(?: (.*))?$/', $value, $match) !== 1) {
        return $value;
    }
    if (isset($match[2])) {
        $names[$match[1]] = $match[2];
    }

    return $names[$match[1]] ?? $value;
}

/**
 * @return array{self: array<string, float>, inclusive: array<string, float>, components: array<string, float>, files: array<string, string>, total: float, unit: float}
 */
function bench_profile(string $path): array
{
    $handle = fopen($path, 'r') ?: throw new RuntimeException("Cannot read {$path}.");
    /** @var array<int|string, string> $files */
    $files = [];
    /** @var array<int|string, string> $functions */
    $functions = [];
    $self = [];
    $inclusive = [];
    $components = [];
    $functionFile = [];
    $file = '';
    $function = '';
    $calleeFile = '';
    $afterCall = false;
    $unit = 1.0;
    $total = 0.0;
    while (($line = fgets($handle)) !== false) {
        $line = rtrim($line, "\n");
        if (str_starts_with($line, 'events:')) {
            // Xdebug 3 writes "events: Time_(10ns) Memory_(bytes)".
            $unit = str_contains($line, 'Time_(10ns)') ? 0.00001 : 0.001;
        } elseif (str_starts_with($line, 'summary:')) {
            $total = (float) trim(substr($line, 8));
        } elseif (str_starts_with($line, 'fl=')) {
            $file = bench_name(substr($line, 3), $files);
        } elseif (str_starts_with($line, 'fn=')) {
            $function = bench_name(substr($line, 3), $functions);
            $functionFile[$function] = $file;
        } elseif (str_starts_with($line, 'cfl=') || str_starts_with($line, 'cfi=')) {
            $calleeFile = bench_name(substr($line, 4), $files);
        } elseif (str_starts_with($line, 'cfn=')) {
            bench_name(substr($line, 4), $functions);
        } elseif (str_starts_with($line, 'calls=')) {
            $afterCall = true;
        } elseif (preg_match('/^\d+ (\d+)/', $line, $match) === 1) {
            $cost = (float) $match[1];
            if ($file === 'php:internal') {
                // Counted at the call of the internal function.
                $afterCall = false;
                continue;
            }
            $inclusive[$function] = ($inclusive[$function] ?? 0) + $cost;
            if (!$afterCall || $calleeFile === 'php:internal') {
                $self[$function] = ($self[$function] ?? 0) + $cost;
                $components[bench_component($file)] = ($components[bench_component($file)] ?? 0) + $cost;
            }
            $afterCall = false;
        }
    }
    fclose($handle);

    return ['self' => $self, 'inclusive' => $inclusive, 'components' => $components, 'files' => $functionFile, 'total' => $total, 'unit' => $unit];
}

foreach (array_slice(bench_arguments(), 1) as $path) {
    $profile = bench_profile($path);
    $ms = static fn(float $cost): string => number_format($cost * $profile['unit'], 2, '.', '');
    $total = $profile['total'] > 0 ? $profile['total'] : array_sum($profile['components']);
    echo sprintf("### %s\n\nTotal %s ms with the profiler (the profiler slows PHP down; compare shares, not times).\n\n| Component | ms | share |\n|---|---:|---:|\n", basename($path), $ms($total));
    arsort($profile['components']);
    foreach ($profile['components'] as $component => $cost) {
        echo sprintf("| %s | %s | %.1f %% |\n", $component, $ms($cost), 100 * $cost / $total);
    }

    $ours = static fn(string $function): bool => in_array(bench_component($profile['files'][$function] ?? ''), ['framework', 'Twig (with compiled templates)'], true);
    foreach (['inclusive' => 'including what they call', 'self' => 'own time and PHP functions they call'] as $kind => $description) {
        $costs = array_filter($profile[$kind], $ours, ARRAY_FILTER_USE_KEY);
        arsort($costs);
        echo sprintf("\nFunctions of the framework and Twig, %s:\n\n| Function | ms | share |\n|---|---:|---:|\n", $description);
        foreach (array_slice($costs, 0, 10, true) as $function => $cost) {
            $short = preg_replace('/^WebCodingCat\\\\ThemeBench\\\\Vendor\\\\/', '', $function);
            echo sprintf("| `%s` | %s | %.1f %% |\n", $short, $ms($cost), 100 * $cost / $total);
        }
    }
    echo "\n";
}
