<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Post;

use Saij\WpAppFramework\Block\RichText;
use Saij\WpAppFramework\Post\PostMeta;

#[PostMeta('details', title: 'Event details', postTypes: [Event::class])]
final class EventDetails
{
    public function __construct(
        private string $start = '',
        private int $capacity = 0,
        private EventKind $kind = EventKind::Concert,
        #[RichText]
        private string $note = '',
    ) {}

    public function getStart(): string
    {
        return $this->start;
    }

    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getKind(): EventKind
    {
        return $this->kind;
    }

    public function setKind(EventKind $kind): void
    {
        $this->kind = $kind;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }
}
