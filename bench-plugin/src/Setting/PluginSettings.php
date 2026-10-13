<?php

declare(strict_types=1);

namespace Saij\BenchPlugin\Setting;

use Saij\WpAppFramework\Setting\SettingsGroup;

#[SettingsGroup('options', title: 'Options')]
final readonly class PluginSettings
{
    public function __construct(
        public bool $enabled = true,
        public int $limit = 10,
    ) {}
}
