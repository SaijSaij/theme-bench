<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Media;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Twig\TemplateRenderer;
use Saij\WpAppFramework\View\ViewData;

/**
 * Static assets: a versioned URL, an image with variants, an inline SVG and an asset of the plugin the theme
 * overrides; view data for its view script.
 */
#[Block('media', title: 'Bench media', category: BlockCategory::Media)]
final readonly class Media
{
    public function __construct(
        private TemplateRenderer $renderer,
        private ViewData $viewData,
    ) {}

    public function render(): string
    {
        $this->viewData->set('media', ['label' => 'Media']);

        return $this->renderer->render('Block/Media/Media.twig');
    }
}
