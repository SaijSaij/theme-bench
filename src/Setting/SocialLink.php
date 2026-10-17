<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Setting;

use WebCodingCat\WpAppFramework\Value\Url;

final readonly class SocialLink
{
    public function __construct(
        public string $label = '',
        #[Url]
        public string $url = '',
    ) {}
}
