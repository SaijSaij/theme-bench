<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Post;

use Saij\WpAppFramework\Post\AbstractPost;
use Saij\WpAppFramework\Post\PostType;
use Saij\WpAppFramework\Post\PostTypeSupport;

#[PostType('event', label: 'Events', singularLabel: 'Event', supports: [PostTypeSupport::Title, PostTypeSupport::Editor], archive: true)]
final class Event extends AbstractPost {}
