<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Variant;

use WebCodingCat\ThemeBench\Post\Genre;
use WebCodingCat\ThemeBench\Setting\GeneralSettings;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Cache\Cache;
use WebCodingCat\WpAppFramework\Cache\Tag\SettingsTag;
use WebCodingCat\WpAppFramework\Cache\Tag\TaxonomyTag;
use WebCodingCat\WpAppFramework\Cache\Vary\LoggedInVary;
use WebCodingCat\WpAppFramework\Cache\Vary\QueryParamVary;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * A cached block with variants and tags of settings and a taxonomy; it enqueues a style in render(), which a cache
 * hit enqueues again (registered on init by VariantStyle, as the framework requires for cached blocks).
 */
#[Block('variant', title: 'Bench variant', category: BlockCategory::Widgets)]
#[Cache(ttl: 3600, tags: [new SettingsTag(GeneralSettings::class), new TaxonomyTag(Genre::class)], vary: [new QueryParamVary('variant'), new LoggedInVary()])]
final readonly class Variant
{
    public function __construct(
        private TemplateRenderer $renderer,
        private GeneralSettings $settings,
    ) {}

    public function render(): string
    {
        wp_enqueue_style(VariantStyle::HANDLE);
        $variant = isset($_GET['variant']) && is_string($_GET['variant']) ? sanitize_key($_GET['variant']) : '';

        return $this->renderer->render('Block/Variant/Variant.twig', ['tagline' => $this->settings->tagline, 'variant' => $variant]);
    }
}
