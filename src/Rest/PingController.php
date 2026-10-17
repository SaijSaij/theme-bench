<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Rest;

use WebCodingCat\WpAppFramework\Rest\HttpMethod;
use WebCodingCat\WpAppFramework\Rest\Permission\PublicAccess;
use WebCodingCat\WpAppFramework\Rest\RestController;
use WebCodingCat\WpAppFramework\Rest\RestRoute;

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
