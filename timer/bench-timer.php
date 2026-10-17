<?php

/**
 * Plugin Name: Bench Timer
 * Description: Measures the phases of a request for the benchmark of wp-app-framework. Active only for requests with the header X-Bench: 1, which get the timings appended as HTML comment <!--bench-timing:{json}-->. Also registers a REST route without the framework for comparison.
 * Version: 0.1.0
 * Requires at least: 7.1
 * Requires PHP: 8.5
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

declare(strict_types=1);

// Code coverage of the framework (bin/bench --coverage, Xdebug in mode coverage): started before every other plugin,
// since bin/bench installs this plugin as 0-bench-timer and WordPress loads plugins in alphabetical order. WP-Cron runs
// due events in requests to wp-cron.php of its own, also without the header; in mode coverage they are covered too.
$benchCoverage = ($_SERVER['HTTP_X_BENCH_COVERAGE'] ?? '') === '1'
    || (defined('DOING_CRON') && function_exists('xdebug_info') && in_array('coverage', (array) xdebug_info('mode'), true));
if ($benchCoverage && function_exists('xdebug_start_code_coverage')) {
    xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
    register_shutdown_function(static function (): void {
        $covered = [];
        foreach (xdebug_get_code_coverage() as $file => $lines) {
            if (str_contains($file, '/webcodingcat/wp-app-framework/src/')) {
                $covered[$file] = $lines;
            }
        }
        if (!is_dir('/tmp/coverage')) {
            mkdir('/tmp/coverage', 0777, true);
        }
        file_put_contents('/tmp/coverage/' . uniqid('', true) . '.json', (string) json_encode($covered));
    });
}

// The database queries of a request with their callers (bin/bench --profile), appended as HTML comment.
if (($_SERVER['HTTP_X_BENCH_QUERIES'] ?? '') === '1') {
    /** @var list<string> $benchQueries */
    $benchQueries = [];
    add_filter('query', static function (string $query) use (&$benchQueries): string {
        $benchQueries[] = preg_replace('/\s+/', ' ', substr($query, 0, 160)) . "\n    " . wp_debug_backtrace_summary(null, 3);

        return $query;
    });
    add_action('shutdown', static function () use (&$benchQueries): void {
        echo "<!--bench-queries\n" . str_replace('--', '- -', implode("\n", $benchQueries)) . "\n-->";
    }, PHP_INT_MIN);
}

if (($_SERVER['HTTP_X_BENCH'] ?? '') === '1') {
    /** @var array<string, float> $benchMarks Seconds since the start of the request, by mark. */
    $benchMarks = ['plugin' => microtime(true)];
    $benchMark = static function (string $name) use (&$benchMarks): void {
        $benchMarks[$name] = microtime(true);
    };
    $benchMemory = [];

    // functions.php of the theme, and with it the boot of the framework, loads between these two actions.
    add_action('setup_theme', static fn() => $benchMark('setup_theme'), PHP_INT_MIN);
    add_action('after_setup_theme', static function () use ($benchMark, &$benchMemory): void {
        $benchMark('after_setup_theme');
        $benchMemory['theme'] = memory_get_usage();
    }, PHP_INT_MAX);
    add_action('wp_loaded', static fn() => $benchMark('wp_loaded'), PHP_INT_MAX);
    add_action('shutdown', static function () use (&$benchMarks, &$benchMemory): void {
        $start = is_numeric($_SERVER['REQUEST_TIME_FLOAT'] ?? null) ? (float) $_SERVER['REQUEST_TIME_FLOAT'] : $benchMarks['plugin'];
        $end = microtime(true);
        $at = static fn(string $mark): float => $benchMarks[$mark] ?? $end;
        $milliseconds = static fn(float $seconds): float => round($seconds * 1000, 3);
        echo '<!--bench-timing:' . json_encode([
            // WordPress up to the action setup_theme: core, mu-plugins, plugins.
            'core' => $milliseconds($at('setup_theme') - $start),
            // functions.php of the theme and after_setup_theme: the boot of the framework.
            'theme' => $milliseconds($at('after_setup_theme') - $at('setup_theme')),
            // init and wp_loaded: registration of blocks, post types, settings, hooks.
            'init' => $milliseconds($at('wp_loaded') - $at('after_setup_theme')),
            // The request itself: query and template, or the REST dispatch.
            'main' => $milliseconds($end - $at('wp_loaded')),
            'total' => $milliseconds($end - $start),
            'memoryTheme' => $benchMemory['theme'] ?? 0,
            'memoryPeak' => memory_get_peak_usage(),
            'queries' => get_num_queries(),
        ]) . '-->';
    }, PHP_INT_MIN);
}

// The counterpart of the framework route bench/v1/ping, registered with WordPress alone.
add_action('rest_api_init', static function (): void {
    register_rest_route('bench-plain/v1', '/ping', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'args' => ['value' => ['type' => 'integer', 'default' => 1]],
        'callback' => static fn(WP_REST_Request $request): array => ['pong' => $request->get_param('value')],
    ]);
});
