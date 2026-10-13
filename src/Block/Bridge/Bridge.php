<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Bridge;

use Saij\BenchPlugin\Api\NoteApi;
use Saij\WpAppFramework\Api\PluginApi;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
