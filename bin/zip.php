<?php

/**
 * Packs a directory into a ZIP with the directory itself as root, like `zip -r`.
 *
 * Usage: php bin/zip.php <directory> <zip>
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

[, $directory, $target] = bench_arguments() + array_fill(0, 3, '');
$directory = rtrim($directory, '/');
if (!is_dir($directory) || $target === '') {
    fwrite(STDERR, "Usage: php bin/zip.php <directory> <zip>\n");
    exit(2);
}

$zip = new ZipArchive();
if ($zip->open($target, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    fwrite(STDERR, "ERROR: cannot write {$target}.\n");
    exit(1);
}
$root = dirname($directory);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
foreach ($files as $file) {
    if ($file instanceof SplFileInfo && $file->isFile()) {
        $zip->addFile($file->getPathname(), substr($file->getPathname(), strlen($root) + 1));
    }
}
$zip->close();
