<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\References;

use Saij\ThemeBench\Post\Event;
use Saij\WpAppFramework\Value\PostReference;
use Saij\WpAppFramework\Value\TermReference;
use Saij\WpAppFramework\Value\UserReference;

final readonly class ReferencesAttributes
{
    /**
     * @param list<int> $events
     */
    public function __construct(
        #[PostReference(postTypes: ['page'])]
        public ?int $page = null,
        #[PostReference(postTypes: ['bench_event'])]
        public array $events = [],
        #[TermReference(taxonomies: ['bench_genre'])]
        public ?int $genre = null,
        #[UserReference]
        public ?int $author = null,
        public ?Event $event = null,
    ) {}
}
