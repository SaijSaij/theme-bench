<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Post;

enum EventKind: string
{
    case Concert = 'concert';
    case Talk = 'talk';
}
