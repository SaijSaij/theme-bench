<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\EventLoop;

use Saij\ThemeBench\Post\Event;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Block\CoreBlock;
use Saij\WpAppFramework\Block\InnerBlocks;
use Saij\WpAppFramework\Block\Repeater;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * Repeats its inner blocks for 20 events (Repeater): a framework block reading its item and a core block.
 */
#[Block('event-loop', title: 'Bench event loop', category: BlockCategory::Widgets)]
#[InnerBlocks(allowed: [EventCard::class, CoreBlock::PostTitle])]
final readonly class EventLoop
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(Repeater $repeater): string
    {
        $items = array_map($repeater->post(...), Event::query()->limit(20)->get());

        return $this->renderer->render('Block/EventLoop/EventLoop.twig', ['items' => $items]);
    }
}
