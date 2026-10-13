<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Block\Card;

use Saij\WpAppFramework\Block\Block;
use Saij\WpAppFramework\Block\BlockCategory;
use Saij\WpAppFramework\Twig\TemplateRenderer;

/**
 * A title, an enum and a list of value objects: what the value factory and Twig do for typical attributes.
 */
#[Block('card', title: 'Bench card', category: BlockCategory::Design)]
final readonly class Card
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function render(CardAttributes $attrs): string
    {
        return $this->renderer->render('Block/Card/Card.twig', ['attrs' => $attrs]);
    }
}
