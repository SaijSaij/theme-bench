<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Table;

use DateTimeImmutable;
use DateTimeInterface;
use Saij\WpAppFramework\Table\AbstractRow;
use Saij\WpAppFramework\Table\Indexed;
use Saij\WpAppFramework\Table\PrimaryKey;
use Saij\WpAppFramework\Table\Table;

/**
 * A row of the table wp_bench_log: a typical log with an enum, a nullable column, a time and an index of two columns.
 */
#[Table('log')]
final class BenchLog extends AbstractRow
{
    public function __construct(
        #[PrimaryKey]
        private ?int $id = null,
        #[Indexed(group: 'level_created', position: 1)]
        private BenchLevel $level = BenchLevel::Info,
        private string $message = '',
        private int $attempts = 0,
        private ?int $postId = null,
        #[Indexed(group: 'level_created', position: 2)]
        private DateTimeImmutable $created = new DateTimeImmutable(),
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    /**
     * @return array{id: int|null, level: string, message: string, attempts: int, postId: int|null, created: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level->value,
            'message' => $this->message,
            'attempts' => $this->attempts,
            'postId' => $this->postId,
            'created' => $this->created->format(DateTimeInterface::ATOM),
        ];
    }
}
