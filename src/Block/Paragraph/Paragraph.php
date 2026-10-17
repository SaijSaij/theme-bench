<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Paragraph;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * Text stored as HTML in the post content, included in automatic excerpts.
 */
#[Block('paragraph', title: 'Bench paragraph', category: BlockCategory::Text)]
final readonly class Paragraph
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(ParagraphAttributes $attrs): string
    {
        return $this->renderer->render('Block/Paragraph/Paragraph.twig', ['attrs' => $attrs]);
    }
}
