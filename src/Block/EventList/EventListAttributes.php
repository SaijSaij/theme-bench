<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\EventList;

use Saij\WpAppFramework\Value\TermReference;

final readonly class EventListAttributes
{
    public function __construct(
        public int $minCapacity = 10,
        #[TermReference(taxonomies: ['bench_genre'])]
        public ?int $genre = null,
        public bool $cached = false,
    ) {}
}
