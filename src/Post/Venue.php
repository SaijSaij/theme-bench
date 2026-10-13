<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Post;

use Saij\WpAppFramework\Post\AbstractPost;
use Saij\WpAppFramework\Post\PostType;
use Saij\WpAppFramework\Post\PostTypeSupport;

/**
 * A post type without the block editor: its meta is edited in the meta box of the classic screen.
 */
#[PostType('venue', label: 'Venues', singularLabel: 'Venue', public: false, supports: [PostTypeSupport::Title])]
final class Venue extends AbstractPost {}
