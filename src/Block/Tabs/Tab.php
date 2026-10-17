<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Tabs;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Block\Context;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

#[Block('tab', title: 'Bench tab', category: BlockCategory::Design, parent: [Tabs::class])]
final readonly class Tab
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(
        TabAttributes $attrs,
        #[Context(Tabs::class, 'active')]
        int $active = 0,
    ): string {
        return $this->renderer->render('Block/Tabs/Tab.twig', ['attrs' => $attrs, 'active' => $active]);
    }
}
