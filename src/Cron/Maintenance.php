<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Cron;

use Saij\WpAppFramework\Cron\CronEvent;
use Saij\WpAppFramework\Cron\CronInterval;
use Saij\WpAppFramework\Cron\CronTask;

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

    #[CronTask]
    public function reindex(Reindex $task): void
    {
        clean_post_cache($task->postId);
    }
}
