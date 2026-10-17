<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Tabs;

use WebCodingCat\WpAppFramework\Block\ProvidesContext;

final readonly class TabsAttributes
{
    public function __construct(
        #[ProvidesContext]
        public int $active = 1,
    ) {}
}
