<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Panel;

use Saij\ThemeBench\Support\Reveal;
use Saij\ThemeBench\Support\Surface;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Block\Support\UseSupport;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
