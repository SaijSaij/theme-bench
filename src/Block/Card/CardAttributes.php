<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Card;

use Saij\WpAppFramework\Value\ListOf;
use Saij\WpAppFramework\Value\PlainText;

final readonly class CardAttributes
{
    /**
     * @param list<CardLink> $links
     */
    public function __construct(
        #[PlainText]
        public string $title = '',
        public Tone $tone = Tone::Light,
        #[ListOf(CardLink::class)]
        public array $links = [],
    ) {}
}
