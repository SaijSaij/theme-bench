<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Text;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * A heading and a paragraph: the counterpart of core/heading plus core/paragraph.
 */
#[Block('text', title: 'Bench text', category: BlockCategory::Text)]
final readonly class Text
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(TextAttributes $attrs): string
    {
        return $this->renderer->render('Block/Text/Text.twig', ['attrs' => $attrs]);
    }
}
