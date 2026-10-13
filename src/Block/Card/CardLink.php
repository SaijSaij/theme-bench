<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Card;

use Saij\WpAppFramework\Block\PlainText;
use Saij\WpAppFramework\Value\Url;

final readonly class CardLink
{
    public function __construct(
        #[PlainText]
        public string $label = '',
        #[Url]
        public string $url = '',
    ) {}
}
