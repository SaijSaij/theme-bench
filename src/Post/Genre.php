<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Post;

use Saij\WpAppFramework\Post\Taxonomy;

#[Taxonomy('genre', label: 'Genres', singularLabel: 'Genre', postTypes: [Event::class])]
final class Genre {}
