<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\EventList;

use Saij\ThemeBench\Post\Event;
use Saij\ThemeBench\Post\EventDetailsFields;
use Saij\ThemeBench\Post\EventKind;
use Saij\ThemeBench\Post\Genre;
use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Query\Any;
use Saij\WpAppFramework\Query\PostOrder;
use Saij\WpAppFramework\Query\Terms;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * A query of post objects with conditions on meta fields and terms, sorted by a meta field; optionally cached.
 */
#[Block('event-list', title: 'Bench event list', category: BlockCategory::Widgets)]
final readonly class EventList
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(EventListAttributes $attrs): string
    {
        $query = Event::query()
            ->where(EventDetailsFields::capacity()->greaterThan($attrs->minCapacity))
            ->where(Any::of(EventDetailsFields::kind()->equals(EventKind::Concert), EventDetailsFields::kind()->equals(EventKind::Talk)))
            ->orderBy(EventDetailsFields::start())
            ->orderBy(PostOrder::Title)
            ->limit(20);
        if ($attrs->genre !== null) {
            $query = $query->where(Terms::in(Genre::class, [$attrs->genre]));
        }
        if ($attrs->cached) {
            $query = $query->cache(600);
        }

        return $this->renderer->render('Block/EventList/EventList.twig', ['events' => $query->get()]);
    }
}
