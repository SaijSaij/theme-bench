<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Asset;

use Saij\WpAppFramework\Asset\MediaLibrary;
use Saij\WpAppFramework\Rest\HttpMethod;
use Saij\WpAppFramework\Rest\Permission\Capability;
use Saij\WpAppFramework\Rest\RestController;
use Saij\WpAppFramework\Rest\RestRoute;

/**
 * Imports an asset into the media library (coverage only: it writes).
 */
#[RestController('v1', base: '/media')]
final readonly class MediaController
{
    public function __construct(private MediaLibrary $media) {}

    /**
     * @return array{id: int}
     */
    #[RestRoute('', HttpMethod::Post, new Capability('upload_files'))]
    public function import(): array
    {
        return ['id' => $this->media->import('images/photo.jpg')];
    }
}
