<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Cron;

use WebCodingCat\WpAppFramework\Cron\CronEvent;
use WebCodingCat\WpAppFramework\Cron\CronInterval;
use WebCodingCat\WpAppFramework\Cron\CronTask;

/**
 * Cron events with an interval in seconds and with a start time, which the framework checks on every request, and
 * the handler of a task.
 */
final readonly class Maintenance
{
    #[CronEvent('sweep', 2 * 3600)]
    public function sweep(): void
    {
        delete_transient('bench_sweep');
    }

    #[CronEvent('nightly', CronInterval::Daily, at: '03:00')]
    public function nightly(): void
    {
        delete_transient('bench_nightly');
    }

    #[CronTask('reindex')]
    public function reindex(Reindex $task): void
    {
        clean_post_cache($task->postId);
    }
}
