<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Asset;

use WebCodingCat\WpAppFramework\Asset\MediaLibrary;
use WebCodingCat\WpAppFramework\Rest\HttpMethod;
use WebCodingCat\WpAppFramework\Rest\Permission\Capability;
use WebCodingCat\WpAppFramework\Rest\RestController;
use WebCodingCat\WpAppFramework\Rest\RestRoute;

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
