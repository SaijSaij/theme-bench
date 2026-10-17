<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\EventLoop;

use WebCodingCat\ThemeBench\Post\Event;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Block\Item;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

/**
 * The current event of an event loop (#[Item]).
 */
#[Block('event-card', title: 'Bench event card', category: BlockCategory::Widgets)]
final readonly class EventCard
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(#[Item] ?Event $event): string
    {
        return $this->renderer->render('Block/EventLoop/EventCard.twig', ['event' => $event]);
    }
}
