<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Banner;

use Saij\ThemeBench\Setting\GeneralSettings;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * Settings in a service (constructor) and in Twig (global settings).
 */
#[Block('banner', title: 'Bench banner', category: BlockCategory::Widgets)]
final readonly class Banner
{
    public function __construct(
        private TemplateRenderer $renderer,
        private GeneralSettings $settings,
    ) {}

    public function render(): string
    {
        return $this->renderer->render('Block/Banner/Banner.twig', ['density' => $this->settings->density]);
    }
}
