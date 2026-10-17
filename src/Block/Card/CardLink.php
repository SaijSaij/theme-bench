<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Card;

use WebCodingCat\WpAppFramework\Value\PlainText;
use WebCodingCat\WpAppFramework\Value\Url;

final readonly class CardLink
{
    public function __construct(
        #[PlainText]
        public string $label = '',
        #[Url]
        public string $url = '',
    ) {}
}
