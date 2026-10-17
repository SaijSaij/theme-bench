<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\References;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * References to posts, terms and users, resolved by the value factory and printed with WordPress functions.
 */
#[Block('references', title: 'Bench references', category: BlockCategory::Widgets)]
final readonly class References
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(ReferencesAttributes $attrs): string
    {
        return $this->renderer->render('Block/References/References.twig', ['attrs' => $attrs]);
    }
}
