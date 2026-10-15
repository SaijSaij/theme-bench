<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Showcase;

use Saij\WpAppFramework\Value\Image;
use Saij\WpAppFramework\Value\PostReference;

final readonly class ShowcaseAttributes
{
    public function __construct(
        #[Image]
        public ?int $image = null,
        #[PostReference(postTypes: ['post'])]
        public ?int $post = null,
    ) {}
}
