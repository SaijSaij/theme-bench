<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Text;

use WebCodingCat\WpAppFramework\Value\PlainText;
use WebCodingCat\WpAppFramework\Value\RichText;

final readonly class TextAttributes
{
    public function __construct(
        #[PlainText]
        public string $title = '',
        #[RichText]
        public string $body = '',
    ) {}
}
