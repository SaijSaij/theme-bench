<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Panel;

use WebCodingCat\ThemeBench\Support\Reveal;
use WebCodingCat\ThemeBench\Support\Surface;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Block\Support\UseSupport;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * A block with two block supports and view scripts (its own and the support's), a template function and a filter.
 */
#[Block('panel', title: 'Bench panel', category: BlockCategory::Design)]
#[UseSupport(Surface::class)]
#[UseSupport(Reveal::class, default: true)]
final readonly class Panel
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(PanelAttributes $attrs): string
    {
        return $this->renderer->render('Block/Panel/Panel.twig', ['attrs' => $attrs]);
    }
}
