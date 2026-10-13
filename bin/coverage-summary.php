<?php

/**
 * Summarizes the code coverage of the framework that bin/bench --coverage collected: per directory and file of the
 * framework's src/ the executed lines, the files no request loaded, and the functions no request executed. The
 * framework copies of all instances (theme, plugin) count together.
 *
 * Usage: php bin/coverage-summary.php <directory of coverage JSON files> <src/ of the framework checkout>
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
 * Line states of all requests by file relative to src/: true executed, false executable but not executed.
 *
 * @return array<string, array<int, bool>>
 */
function bench_coverage(string $directory): array
{
    $coverage = [];
    foreach (glob($directory . '/*.json') ?: [] as $file) {
        $data = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach (is_array($data) ? $data : [] as $path => $lines) {
            $position = strpos((string) $path, '/saij/wp-app-framework/src/');
            if ($position === false || !is_array($lines)) {
                continue;
            }
            $relative = substr((string) $path, $position + strlen('/saij/wp-app-framework/src/'));
            foreach ($lines as $line => $state) {
                // Xdebug: 1 executed, -1 executable but not executed, -2 dead code.
                if ($state === -2) {
                    continue;
                }
                $coverage[$relative][(int) $line] = ($coverage[$relative][(int) $line] ?? false) || $state === 1;
            }
        }
    }
    ksort($coverage);

    return $coverage;
}

/**
 * The named functions and methods of a source file with their first and last line.
 *
 * @return list<array{name: string, start: int, end: int}>
 */
function bench_functions(string $file): array
{
    $tokens = array_values(PhpToken::tokenize((string) file_get_contents($file)));
    $functions = [];
    $class = '';
    $count = count($tokens);
    for ($i = 0; $i < $count; $i++) {
        $token = $tokens[$i];
        if ($token->is([T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM])) {
            $name = bench_next($tokens, $i);
            if ($name !== null && $name->is(T_STRING)) {
                $class = $name->text;
            }
            continue;
        }
        if (!$token->is(T_FUNCTION)) {
            continue;
        }
        $name = bench_next($tokens, $i);
        if ($name === null || !$name->is(T_STRING)) {
            // A closure counts as part of its function.
            continue;
        }
        // The body: from the first "{" after the signature (none for abstract and interface methods) to its "}".
        $depth = 0;
        $start = null;
        for ($j = $i; $j < $count; $j++) {
            if ($start === null && $tokens[$j]->text === ';') {
                break;
            }
            if ($tokens[$j]->text === '{' || $tokens[$j]->is([T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES])) {
                $start ??= $tokens[$j]->line;
                $depth++;
            } elseif ($tokens[$j]->text === '}') {
                $depth--;
                if ($depth === 0) {
                    $functions[] = ['name' => ($class === '' ? '' : $class . '::') . $name->text . '()', 'start' => $token->line, 'end' => $tokens[$j]->line];
                    break;
                }
            }
        }
    }

    return $functions;
}

/**
 * @param array<int, PhpToken> $tokens
 */
function bench_next(array $tokens, int $index): ?PhpToken
{
    for ($i = $index + 1, $count = count($tokens); $i < $count; $i++) {
        if (!$tokens[$i]->isIgnorable() && $tokens[$i]->text !== '&') {
            return $tokens[$i];
        }
    }

    return null;
}

[, $coverageDirectory, $source] = bench_arguments() + array_fill(0, 3, '');
$coverage = bench_coverage($coverageDirectory);
$source = rtrim($source, '/');
$all = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS)) as $file) {
    if ($file instanceof SplFileInfo && $file->getExtension() === 'php') {
        $all[] = substr($file->getPathname(), strlen($source) + 1);
    }
}
sort($all);

$directories = [];
foreach ($all as $relative) {
    $directory = str_contains($relative, '/') ? dirname($relative) : '.';
    $lines = $coverage[$relative] ?? null;
    $directories[$directory] ??= ['files' => 0, 'loaded' => 0, 'lines' => 0, 'executed' => 0];
    $directories[$directory]['files']++;
    if ($lines !== null) {
        $directories[$directory]['loaded']++;
        $directories[$directory]['lines'] += count($lines);
        $directories[$directory]['executed'] += count(array_filter($lines));
    }
}

echo "# Code coverage of wp-app-framework\n\n";
echo sprintf("Lines of `src/` executed by the requests of all scenarios (%d requests), the framework copies of the theme and the plugin together. Files no request loaded count with no executable lines.\n\n", count(glob($coverageDirectory . '/*.json') ?: []));
echo "| Directory | files | loaded | executed lines | share |\n|---|---:|---:|---:|---:|\n";
foreach ($directories as $name => $stats) {
    echo sprintf(
        "| `%s` | %d | %d | %d of %d | %s |\n",
        $name,
        $stats['files'],
        $stats['loaded'],
        $stats['executed'],
        $stats['lines'],
        $stats['lines'] > 0 ? sprintf('%.0f %%', 100 * $stats['executed'] / $stats['lines']) : '–',
    );
}

echo "\n## Files no request loaded\n\n";
foreach ($all as $relative) {
    if (!isset($coverage[$relative])) {
        echo sprintf("- `%s`\n", $relative);
    }
}

echo "\n## Functions no request executed\n\nIn the files that were loaded; closures count as part of their function.\n\n";
foreach ($all as $relative) {
    $lines = $coverage[$relative] ?? null;
    if ($lines === null) {
        continue;
    }
    $missing = [];
    foreach (bench_functions($source . '/' . $relative) as $function) {
        $executed = false;
        $executable = false;
        foreach ($lines as $line => $state) {
            if ($line >= $function['start'] && $line <= $function['end']) {
                $executable = true;
                $executed = $executed || $state;
            }
        }
        if ($executable && !$executed) {
            $missing[] = $function['name'];
        }
    }
    if ($missing !== []) {
        echo sprintf("- `%s`: %s\n", $relative, implode(', ', array_map(static fn(string $name): string => '`' . $name . '`', $missing)));
    }
}
