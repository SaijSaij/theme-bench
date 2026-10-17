<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\EventList;

use WebCodingCat\ThemeBench\Post\Event;
use WebCodingCat\ThemeBench\Post\EventDetailsFields;
use WebCodingCat\ThemeBench\Post\EventKind;
use WebCodingCat\ThemeBench\Post\Genre;
use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Query\Any;
use WebCodingCat\WpAppFramework\Query\PostOrder;
use WebCodingCat\WpAppFramework\Query\Terms;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

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
