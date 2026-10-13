<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Tabs;

use Saij\WpAppFramework\Block\ProvidesContext;

final readonly class TabsAttributes
{
    public function __construct(
        #[ProvidesContext]
        public int $active = 1,
    ) {}
}
