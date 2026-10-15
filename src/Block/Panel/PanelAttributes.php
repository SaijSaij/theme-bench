<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Panel;

use Saij\WpAppFramework\Value\PlainText;

final readonly class PanelAttributes
{
    public function __construct(
        #[PlainText]
        public string $title = '',
        public bool $open = false,
    ) {}
}
