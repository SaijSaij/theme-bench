<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Card;

use WebCodingCat\WpAppFramework\Value\Color;
use WebCodingCat\WpAppFramework\Value\ListOf;
use WebCodingCat\WpAppFramework\Value\PlainText;

final readonly class CardAttributes
{
    /**
     * @param list<CardLink> $links
     */
    public function __construct(
        #[PlainText]
        public string $title = '',
        public Tone $tone = Tone::Light,
        #[ListOf(CardLink::class, max: 3, appender: true)]
        public array $links = [],
        #[Color]
        public string $accent = '',
    ) {}
}
