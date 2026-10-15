<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Text;

use Saij\WpAppFramework\Value\PlainText;
use Saij\WpAppFramework\Value\RichText;

final readonly class TextAttributes
{
    public function __construct(
        #[PlainText]
        public string $title = '',
        #[RichText]
        public string $body = '',
    ) {}
}
