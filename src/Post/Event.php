<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Post;

use WebCodingCat\WpAppFramework\Post\AbstractPost;
use WebCodingCat\WpAppFramework\Post\PostType;
use WebCodingCat\WpAppFramework\Post\PostTypeSupport;

#[PostType('event', label: 'Events', singularLabel: 'Event', supports: [PostTypeSupport::Title, PostTypeSupport::Editor], archive: true)]
final class Event extends AbstractPost {}
