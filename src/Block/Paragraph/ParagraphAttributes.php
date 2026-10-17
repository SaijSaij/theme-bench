<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Paragraph;

use WebCodingCat\WpAppFramework\Value\RichText;

final readonly class ParagraphAttributes
{
    public function __construct(
        #[RichText(postContent: true, excerpt: true)]
        public string $text = '',
    ) {}
}
