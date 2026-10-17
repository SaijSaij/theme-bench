<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Twig;

use WebCodingCat\WpAppFramework\Twig\TemplateFilter;
use WebCodingCat\WpAppFramework\Twig\TemplateFunction;

final class TextHelpers
{
    #[TemplateFunction('bench_greeting')]
    public function greeting(string $name): string
    {
        return 'Hello ' . $name;
    }

    #[TemplateFilter('bench_emphasize', html: true)]
    public function emphasize(string $value): string
    {
        return '<em>' . $value . '</em>';
    }
}
