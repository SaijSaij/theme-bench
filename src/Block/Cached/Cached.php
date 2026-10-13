<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Cached;

use Saij\ThemeBench\Block\Text\TextAttributes;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Cache\Cache;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
