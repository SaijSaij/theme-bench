<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Rest;

final readonly class Item
{
    public function __construct(
        public string $name = '',
        public int $amount = 0,
    ) {}
}
