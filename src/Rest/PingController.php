<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Rest;

use Saij\WpAppFramework\Rest\HttpMethod;
use Saij\WpAppFramework\Rest\Permission\PublicAccess;
use Saij\WpAppFramework\Rest\RestController;
use Saij\WpAppFramework\Rest\RestRoute;

/**
 * A REST route of the framework; the timer plugin registers the same route without the framework.
 */
#[RestController('v1', base: '/ping')]
final class PingController
{
    /**
     * @return array{pong: int}
     */
    #[RestRoute('', HttpMethod::Get, new PublicAccess())]
    public function ping(int $value = 1): array
    {
        return ['pong' => $value];
    }
}
