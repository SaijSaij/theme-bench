<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Support;

use WebCodingCat\WpAppFramework\Block\Support\BlockSupport;
use WebCodingCat\WpAppFramework\Block\Support\SupportGroup;

#[BlockSupport('reveal', label: 'Reveal', group: SupportGroup::Settings)]
final class Reveal
{
    public function className(bool $value = false): ?string
    {
        return $value ? 'reveal' : null;
    }
}
