<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Latest;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Post\Category;
use Saij\WpAppFramework\Post\Page;
use Saij\WpAppFramework\Post\Post;
use Saij\WpAppFramework\Query\All;
use Saij\WpAppFramework\Query\PostOrder;
use Saij\WpAppFramework\Query\Terms;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * Posts and pages of WordPress through the framework's post classes: a page of posts, a count and the first page.
 */
#[Block('latest', title: 'Bench latest posts', category: BlockCategory::Widgets)]
final readonly class Latest
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(): string
    {
        $posts = Post::query()
            ->where(All::of(Terms::exists(Category::class)))
            ->search('Post')
            ->orderBy(PostOrder::Title)
            ->paginate(perPage: 5, page: 1);

        return $this->renderer->render('Block/Latest/Latest.twig', [
            'posts' => $posts,
            'pages' => Page::query()->count(),
            'first' => Page::query()->orderBy(PostOrder::Title)->first(),
        ]);
    }
}
