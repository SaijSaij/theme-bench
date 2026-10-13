<?php

/**
 * Plugin Name: Bench Plugin
 * Description: Plugin of the benchmark of wp-app-framework: a second framework instance with a public API, public template functions, a hook and settings.
 * Version: 0.1.0
 * Requires at least: 7.1
 * Requires PHP: 8.5
 * License: Proprietary
 */

declare(strict_types=1);

// Release builds ship prefixed dependencies in vendor-prefixed/; source checkouts use Composer's vendor/.
require is_file(__DIR__ . '/vendor-prefixed/autoload.php')
    ? __DIR__ . '/vendor-prefixed/autoload.php'
    : __DIR__ . '/vendor/autoload.php';

Saij\WpAppFramework\Plugin::boot(__FILE__);
