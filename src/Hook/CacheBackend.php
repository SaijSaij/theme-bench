<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Hook;

use Saij\WpAppFramework\Hook\Hook;

/**
 * Lets a request choose the cache backend with ?bench-backend=transient, so the benchmark reaches every backend.
 */
final class CacheBackend
{
    /**
     * @param list<string> $backends
     * @return list<string>
     */
    #[Hook('wp_app_framework/cache/backends/theme-bench')]
    public function select(array $backends): array
    {
        return ($_GET['bench-backend'] ?? null) === 'transient' ? ['transient'] : $backends;
    }
}
