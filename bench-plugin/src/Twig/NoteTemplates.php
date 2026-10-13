<?php

declare(strict_types=1);

namespace Saij\BenchPlugin\Twig;

use Saij\WpAppFramework\Twig\TemplateFunction;

final class NoteTemplates
{
    #[TemplateFunction('bench_plugin_note_count', public: true)]
    public function noteCount(): int
    {
        return 2;
    }
}
