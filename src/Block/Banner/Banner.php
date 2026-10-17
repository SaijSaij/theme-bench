<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Banner;

use WebCodingCat\ThemeBench\Setting\GeneralSettings;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

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
