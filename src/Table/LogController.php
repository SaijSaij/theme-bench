<?php

declare(strict_types=1);

namespace Saij\ThemeBench\Table;

use DateTimeImmutable;
use DateTimeZone;
use Saij\WpAppFramework\Query\Any;
use Saij\WpAppFramework\Query\Direction;
use Saij\WpAppFramework\Rest\HttpMethod;
use Saij\WpAppFramework\Rest\Permission\Capability;
use Saij\WpAppFramework\Rest\Permission\PublicAccess;
use Saij\WpAppFramework\Rest\RestController;
use Saij\WpAppFramework\Rest\RestRoute;

/**
 * Rows of a table through REST: a query of 50 rows, a write of one row, and the rows the benchmark seeds.
 */
#[RestController('v1', base: '/log')]
final class LogController
{
    /**
     * @return array{count: int, rows: list<array{id: int|null, level: string, message: string, attempts: int, postId: int|null, created: string}>}
     */
    #[RestRoute('', HttpMethod::Get, new PublicAccess())]
    public function index(): array
    {
        $rows = BenchLog::query()
            ->where(BenchLogFields::created()->greaterThan(new DateTimeImmutable('2026-01-01 00:00:00 UTC')))
            ->where(Any::of(BenchLogFields::level()->equals(BenchLevel::Error), BenchLogFields::attempts()->greaterThan(3)))
            ->orderBy(BenchLogFields::created(), Direction::Desc)
            ->limit(50)
            ->get();

        return ['count' => count($rows), 'rows' => array_map(static fn(BenchLog $row): array => $row->toArray(), $rows)];
    }

    /**
     * @return array{attempts: int}
     */
    #[RestRoute('/{id}', HttpMethod::Post, new Capability('edit_posts'))]
    public function update(int $id, int $attempts): array
    {
        $row = BenchLog::get($id);
        $row->setAttempts($attempts);
        $row->save();

        return ['attempts' => $attempts];
    }

    /**
     * Seeds rows: every third an error, attempts 0 to 9, one row per hour from 2026-01-02.
     *
     * @return array{first: int|null, created: int}
     */
    #[RestRoute('/seed', HttpMethod::Post, new Capability('edit_posts'))]
    public function seed(int $count): array
    {
        $first = null;
        for ($i = 1; $i <= $count; $i++) {
            $row = new BenchLog(
                level: $i % 3 === 0 ? BenchLevel::Error : BenchLevel::Info,
                message: sprintf('Row %d', $i),
                attempts: $i % 10,
                postId: $i % 2 === 0 ? $i : null,
                created: new DateTimeImmutable('2026-01-02 00:00:00', new DateTimeZone('UTC'))->modify(sprintf('+%d hours', $i)),
            );
            $row->save();
            $first ??= $row->getId();
        }

        return ['first' => $first, 'created' => $count];
    }
}
