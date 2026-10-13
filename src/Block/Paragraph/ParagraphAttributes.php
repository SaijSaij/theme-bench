<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Paragraph;

use Saij\WpAppFramework\Block\RichText;

final readonly class ParagraphAttributes
{
    public function __construct(
        #[RichText(postContent: true, excerpt: true)]
        public string $text = '',
    ) {}
}
