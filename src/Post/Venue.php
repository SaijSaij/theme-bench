<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Post;

use WebCodingCat\WpAppFramework\Post\AbstractPost;
use WebCodingCat\WpAppFramework\Post\PostType;
use WebCodingCat\WpAppFramework\Post\PostTypeSupport;

/**
 * A post type without the block editor: its meta is edited in the meta box of the classic screen.
 */
#[PostType('venue', label: 'Venues', singularLabel: 'Venue', public: false, supports: [PostTypeSupport::Title])]
final class Venue extends AbstractPost {}
