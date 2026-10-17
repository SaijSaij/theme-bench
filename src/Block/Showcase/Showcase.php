<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Showcase;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

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
