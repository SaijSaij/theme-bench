<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Cron;

use DateTimeImmutable;
use WebCodingCat\WpAppFramework\Cron\CronScheduler;
use WebCodingCat\WpAppFramework\Rest\HttpMethod;
use WebCodingCat\WpAppFramework\Rest\Permission\PublicAccess;
use WebCodingCat\WpAppFramework\Rest\RestController;
use WebCodingCat\WpAppFramework\Rest\RestRoute;

/**
 * Plans a task for now (bin/bench --coverage); the next WP-Cron run handles it.
 */
#[RestController('v1', base: '/reindex')]
final readonly class ReindexController
{
    public function __construct(private CronScheduler $cron) {}

    /**
     * @return array{scheduled: bool}
     */
    #[RestRoute('', HttpMethod::Post, new PublicAccess())]
    public function schedule(int $postId): array
    {
        return ['scheduled' => $this->cron->schedule(new Reindex($postId), new DateTimeImmutable())];
    }
}
