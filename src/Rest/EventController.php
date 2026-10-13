<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Rest;

use Saij\ThemeBench\Post\Event;
use Saij\ThemeBench\Post\EventDetails;
use Saij\WpAppFramework\Cache\Cache;
use Saij\WpAppFramework\Cache\Tag\PostTypeTag;
use Saij\WpAppFramework\Cache\Vary\HeaderVary;
use Saij\WpAppFramework\Cache\Vary\UserVary;
use Saij\WpAppFramework\Rest\HttpMethod;
use Saij\WpAppFramework\Rest\Permission\Capability;
use Saij\WpAppFramework\Rest\Permission\PublicAccess;
use Saij\WpAppFramework\Rest\RestController;
use Saij\WpAppFramework\Rest\RestException;
use Saij\WpAppFramework\Rest\RestRoute;

/**
 * Post objects through REST: a cached read with variants and a write that saves a post with its meta.
 */
#[RestController('v1', base: '/events')]
final class EventController
{
    /**
     * @return array{title: string, capacity: int}
     */
    #[RestRoute('/{id}', HttpMethod::Get, new PublicAccess())]
    #[Cache(ttl: 300, tags: [new PostTypeTag(Event::class)], vary: [new UserVary(), new HeaderVary('X-Bench-Variant')])]
    public function show(int $id): array
    {
        $event = Event::find($id) ?? throw new RestException('bench_not_found', 'No such event.', 404);

        return ['title' => $event->getTitle(), 'capacity' => $event->meta(EventDetails::class)->getCapacity()];
    }

    /**
     * @return array{capacity: int}
     */
    #[RestRoute('/{id}', HttpMethod::Post, new Capability('edit_posts'))]
    public function update(int $id, int $capacity): array
    {
        $event = Event::get($id);
        $event->meta(EventDetails::class)->setCapacity($capacity);
        $event->setTitle($event->getTitle());
        $event->save();

        return ['capacity' => $capacity];
    }
}
