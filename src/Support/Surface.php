<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Support;

use WebCodingCat\WpAppFramework\Block\Support\BlockSupport;

#[BlockSupport('surface', label: 'Surface')]
final class Surface
{
    public function className(SurfaceTone $value = SurfaceTone::Light): string
    {
        return 'surface-' . $value->value;
    }
}
