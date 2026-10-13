<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Support;

use Saij\WpAppFramework\Block\Support\BlockSupport;
use Saij\WpAppFramework\Block\Support\SupportGroup;

#[BlockSupport('reveal', label: 'Reveal', group: SupportGroup::Settings)]
final class Reveal
{
    public function className(bool $value = false): ?string
    {
        return $value ? 'reveal' : null;
    }
}
