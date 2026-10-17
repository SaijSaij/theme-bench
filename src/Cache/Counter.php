<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Cache;

use WebCodingCat\ThemeBench\Post\Event;
use WebCodingCat\ThemeBench\Rest\Item;
use WebCodingCat\WpAppFramework\Cache\CacheStore;
use WebCodingCat\WpAppFramework\Cache\Tag\PostTypeTag;
use WebCodingCat\WpAppFramework\Rest\HttpMethod;
use WebCodingCat\WpAppFramework\Rest\Permission\Callback;
use WebCodingCat\WpAppFramework\Rest\Permission\PublicAccess;
use WebCodingCat\WpAppFramework\Rest\RestController;
use WebCodingCat\WpAppFramework\Rest\RestRoute;
use WebCodingCat\WpAppFramework\Value\ListOf;

/**
 * REST routes with value class arguments, a callback permission and CacheStore::remember().
 */
#[RestController('v1', base: '/items')]
final readonly class Counter
{
    public function __construct(private CacheStore $cache) {}

    /**
     * @param list<Item> $items
     * @return array{count: int, total: int}
     */
    #[RestRoute('', HttpMethod::Post, new Callback(self::allowed(...)))]
    public function count(#[ListOf(Item::class)] array $items = []): array
    {
        return ['count' => count($items), 'total' => array_sum(array_map(static fn(Item $item): int => $item->amount, $items))];
    }

    /**
     * @return array{posts: int}
     */
    #[RestRoute('/remembered', HttpMethod::Get, new PublicAccess())]
    public function remembered(): array
    {
        $posts = $this->cache->remember('posts', static fn(): int => count(get_posts(['post_type' => 'bench_event', 'numberposts' => -1, 'fields' => 'ids'])), 300, [new PostTypeTag(Event::class)]);

        return ['posts' => $posts];
    }

    public static function allowed(): bool
    {
        return true;
    }
}
