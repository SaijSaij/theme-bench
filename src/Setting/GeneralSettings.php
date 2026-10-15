<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Setting;

use Saij\WpAppFramework\Setting\SettingsGroup;
use Saij\WpAppFramework\Value\ListOf;
use Saij\WpAppFramework\Value\RichText;

#[SettingsGroup('general', title: 'General')]
final readonly class GeneralSettings
{
    /**
     * @param list<SocialLink> $links
     */
    public function __construct(
        public string $tagline = 'Bench tagline',
        public Density $density = Density::Normal,
        #[RichText]
        public string $imprint = '',
        #[ListOf(SocialLink::class)]
        public array $links = [],
    ) {}
}
