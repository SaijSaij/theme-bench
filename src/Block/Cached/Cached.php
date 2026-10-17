<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Cached;

use WebCodingCat\ThemeBench\Block\Text\TextAttributes;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Cache\Cache;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * The text block with #[Cache]: after the first request its output comes from the cache store.
 */
#[Block('cached', title: 'Bench cached text', category: BlockCategory::Text)]
#[Cache(ttl: 3600)]
final readonly class Cached
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(TextAttributes $attrs): string
    {
        return $this->renderer->render('Block/Text/Text.twig', ['attrs' => $attrs]);
    }
}
