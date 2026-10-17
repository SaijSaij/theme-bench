<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Links;

use WebCodingCat\WpAppFramework\Value\Link;

final readonly class LinksAttributes
{
    public function __construct(
        public Link $page = new Link(),
        public Link $genre = new Link(),
        public Link $image = new Link(),
        public Link $external = new Link(),
    ) {}
}
