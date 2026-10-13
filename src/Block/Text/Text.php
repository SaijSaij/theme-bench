<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Text;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
