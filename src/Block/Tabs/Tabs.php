<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Tabs;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Block\InnerBlocks;
use WebCodingCat\WpAppFramework\Block\Supports;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

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
