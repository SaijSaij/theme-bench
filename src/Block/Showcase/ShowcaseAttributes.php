<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Showcase;

use WebCodingCat\WpAppFramework\Value\Image;
use WebCodingCat\WpAppFramework\Value\PostReference;

final readonly class ShowcaseAttributes
{
    public function __construct(
        #[Image]
        public ?int $image = null,
        #[PostReference(postTypes: ['post'])]
        public ?int $post = null,
    ) {}
}
