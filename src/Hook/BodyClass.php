<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Hook;

use WebCodingCat\ThemeBench\Setting\GeneralSettings;
use WebCodingCat\WpAppFramework\Hook\Hook;

/**
 * A filter and an action on every page; the service gets a settings group injected.
 */
final readonly class BodyClass
{
    public function __construct(private GeneralSettings $settings) {}

    /**
     * @param list<string> $classes
     * @return list<string>
     */
    #[Hook('body_class')]
    public function addClass(array $classes): array
    {
        $classes[] = 'bench-' . $this->settings->density->value;

        return $classes;
    }

    #[Hook('wp_footer')]
    public function footer(): void
    {
        echo '<!-- bench footer -->';
    }
}
