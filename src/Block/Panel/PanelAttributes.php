<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Panel;

use WebCodingCat\WpAppFramework\Value\PlainText;

final readonly class PanelAttributes
{
    public function __construct(
        #[PlainText]
        public string $title = '',
        public bool $open = false,
    ) {}
}
