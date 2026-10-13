<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Showcase;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * Every WordPress function of the framework's Twig, the function post() and is_editor().
 */
#[Block('showcase', title: 'Bench showcase', category: BlockCategory::Media)]
final readonly class Showcase
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(ShowcaseAttributes $attrs): string
    {
        return $this->renderer->render('Block/Showcase/Showcase.twig', ['attrs' => $attrs]);
    }
}
