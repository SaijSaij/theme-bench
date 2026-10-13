<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Post;

enum EventKind: string
{
    case Concert = 'concert';
    case Talk = 'talk';
}
