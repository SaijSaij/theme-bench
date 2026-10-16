<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\EventLoop;

use Saij\ThemeBench\Post\Event;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Block\Item;
use Saij\WpAppFramework\Twig\TemplateRenderer;

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
