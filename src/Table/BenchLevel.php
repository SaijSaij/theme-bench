<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Table;

enum BenchLevel: string
{
    case Info = 'info';
    case Error = 'error';
}
