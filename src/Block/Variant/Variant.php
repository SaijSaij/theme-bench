<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Variant;

use Saij\ThemeBench\Post\Genre;
use Saij\ThemeBench\Setting\GeneralSettings;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Cache\Cache;
use Saij\WpAppFramework\Cache\Tag\SettingsTag;
use Saij\WpAppFramework\Cache\Tag\TaxonomyTag;
use Saij\WpAppFramework\Cache\Vary\LoggedInVary;
use Saij\WpAppFramework\Cache\Vary\QueryParamVary;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
