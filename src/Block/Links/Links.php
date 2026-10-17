<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Links;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * Links to a page, a term, a media file and a free URL, resolved by the value factory and printed with
 * link_attributes().
 */
#[Block('links', title: 'Bench links', category: BlockCategory::Widgets)]
final readonly class Links
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(LinksAttributes $attrs): string
    {
        return $this->renderer->render('Block/Links/Links.twig', ['attrs' => $attrs]);
    }
}
