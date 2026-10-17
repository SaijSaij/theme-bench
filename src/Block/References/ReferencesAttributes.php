<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\References;

use WebCodingCat\ThemeBench\Post\Event;
use WebCodingCat\WpAppFramework\Value\PostReference;
use WebCodingCat\WpAppFramework\Value\TermReference;
use WebCodingCat\WpAppFramework\Value\UserReference;

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
