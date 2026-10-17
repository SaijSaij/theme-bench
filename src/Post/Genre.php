<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Post;

use WebCodingCat\WpAppFramework\Post\Taxonomy;

#[Taxonomy('genre', label: 'Genres', singularLabel: 'Genre', postTypes: [Event::class])]
final class Genre {}
