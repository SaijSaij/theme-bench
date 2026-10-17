<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Cron;

/**
 * A one-time task: a post whose caches are cleaned.
 */
final readonly class Reindex
{
    public function __construct(public int $postId) {}
}
