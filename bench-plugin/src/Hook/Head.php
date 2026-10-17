<?php

declare(strict_types=1);

namespace WebCodingCat\BenchPlugin\Hook;

use WebCodingCat\WpAppFramework\Hook\Hook;

final class Head
{
    #[Hook('wp_head')]
    public function marker(): void
    {
        echo '<meta name="bench-plugin" content="1">';
    }
}
