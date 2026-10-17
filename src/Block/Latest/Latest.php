<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Block\Latest;

use WebCodingCat\WpAppFramework\Block\Block;
use WebCodingCat\WpAppFramework\Block\BlockCategory;
use WebCodingCat\WpAppFramework\Post\Category;
use WebCodingCat\WpAppFramework\Post\Page;
use WebCodingCat\WpAppFramework\Post\Post;
use WebCodingCat\WpAppFramework\Query\All;
use WebCodingCat\WpAppFramework\Query\PostOrder;
use WebCodingCat\WpAppFramework\Query\Terms;
use WebCodingCat\WpAppFramework\Twig\TemplateRenderer;

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
