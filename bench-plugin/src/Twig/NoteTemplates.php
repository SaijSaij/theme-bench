<?php

declare(strict_types=1);

namespace WebCodingCat\BenchPlugin\Twig;

use WebCodingCat\WpAppFramework\Asset\Assets;
use WebCodingCat\WpAppFramework\Twig\TemplateFunction;

final readonly class NoteTemplates
{
    public function __construct(private Assets $assets) {}

    /**
     * An asset of the plugin, which the theme overrides (overrides/bench-plugin/assets/).
     */
    #[TemplateFunction('bench_plugin_pin', html: true, public: true)]
    public function pin(): string
    {
        return $this->assets->image('images/pin.svg', '');
    }

    #[TemplateFunction('bench_plugin_note_count', public: true)]
    public function noteCount(): int
    {
        return 2;
    }
}
