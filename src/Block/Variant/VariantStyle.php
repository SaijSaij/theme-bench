<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Variant;

use WebCodingCat\WpAppFramework\Hook\Hook;

/**
 * Registers the style of the block Variant before blocks render.
 */
final class VariantStyle
{
    public const string HANDLE = 'bench-variant';

    #[Hook('init')]
    public function register(): void
    {
        wp_register_style(self::HANDLE, false, [], '1');
        wp_add_inline_style(self::HANDLE, '.variant{outline:1px solid}');
    }
}
