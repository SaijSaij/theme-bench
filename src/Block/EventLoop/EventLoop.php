<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\EventLoop;

use WebCodingCat\ThemeBench\Post\Event;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Block\CoreBlock;
use WebCodingCat\WpAppFramework\Block\InnerBlocks;
use WebCodingCat\WpAppFramework\Block\Repeater;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

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
