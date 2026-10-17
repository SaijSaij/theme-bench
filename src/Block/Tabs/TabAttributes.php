<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Tabs;

use WebCodingCat\WpAppFramework\Value\PlainText;

final readonly class TabAttributes
{
    public function __construct(
        #[PlainText]
        public string $title = '',
    ) {}
}
