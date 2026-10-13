<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Tabs;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Block\Context;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
