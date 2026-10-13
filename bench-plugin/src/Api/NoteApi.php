<?php

declare(strict_types=1);

namespace Saij\BenchPlugin\Api;

use Saij\WpAppFramework\Api\PublicApi;

#[PublicApi]
final class NoteApi
{
    /**
     * @return list<string>
     */
    public function getNotes(): array
    {
        return ['First note', 'Second note'];
    }
}
