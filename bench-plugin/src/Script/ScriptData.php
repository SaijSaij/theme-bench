<?php

declare(strict_types=1);

namespace WebCodingCat\BenchPlugin\Script;

use WebCodingCat\WpAppFramework\Hook\Hook;
use WebCodingCat\WpAppFramework\View\ViewData;

/**
 * View data of the plugin's document view and editor script.
 */
final class ScriptData
{
    #[Hook('wp')]
    public function frontEnd(ViewData $viewData): void
    {
        $viewData->set('plugin', ['label' => 'Bench plugin']);
    }

    #[Hook('enqueue_block_editor_assets')]
    public function editor(ViewData $viewData): void
    {
        $viewData->set('plugin', ['label' => 'Bench plugin editor']);
    }
}
