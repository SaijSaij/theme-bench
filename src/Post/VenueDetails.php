<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Post;

use Saij\WpAppFramework\Post\PostMeta;

#[PostMeta('venue', title: 'Venue details', postTypes: [Venue::class])]
final class VenueDetails
{
    public function __construct(
        private string $city = '',
        private int $seats = 0,
    ) {}

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getSeats(): int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): void
    {
        $this->seats = $seats;
    }
}
