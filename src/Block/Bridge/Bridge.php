<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Bridge;

use WebCodingCat\BenchPlugin\Api\NoteApi;
use WebCodingCat\WpAppFramework\Api\PluginApi;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * The public API and a public template function of the plugin bench-plugin, another framework instance.
 */
#[Block('bridge', title: 'Bench bridge', category: BlockCategory::Widgets)]
final readonly class Bridge
{
    public function __construct(
        private TemplateRenderer $renderer,
        #[PluginApi('bench-plugin')]
        private ?NoteApi $notes = null,
    ) {}

    public function render(): string
    {
        return $this->renderer->render('Block/Bridge/Bridge.twig', ['notes' => $this->notes?->getNotes() ?? []]);
    }
}
