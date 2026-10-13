<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Tabs;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Block\InnerBlocks;
use Saij\WpAppFramework\Block\Supports;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * A container that passes a value to its tabs (block context).
 */
#[Block('tabs', title: 'Bench tabs', category: BlockCategory::Design)]
#[Supports(contentRole: true)]
#[InnerBlocks(allowed: [Tab::class])]
final readonly class Tabs
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(TabsAttributes $attrs, string $content): string
    {
        return $this->renderer->render('Block/Tabs/Tabs.twig', ['attrs' => $attrs, 'content' => $content]);
    }
}
