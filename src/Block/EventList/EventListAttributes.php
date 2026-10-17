<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\EventList;

use WebCodingCat\WpAppFramework\Value\TermReference;

final readonly class EventListAttributes
{
    public function __construct(
        public int $minCapacity = 10,
        #[TermReference(taxonomies: ['bench_genre'])]
        public ?int $genre = null,
        public bool $cached = false,
    ) {}
}
