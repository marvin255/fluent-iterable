<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use Marvin255\FluentIterable\FluentIterable;

/**
 * @internal
 */
class FluentIterableTest extends BaseCase
{
    /**
     * @psalm-param iterable<int, int> $input
     * @psalm-param iterable<int, int> $merge
     * @psalm-param array<int, mixed> $reference
     * @dataProvider provideMergeData
     */
    public function testMerge(iterable $input, iterable $merge, array $reference): void
    {
        $result = FluentIterable::of($input)->merge($merge)->toArray();

        $this->assertSame($reference, $result);
    }

    public function provideMergeData(): array
    {
        return [
            'array with array' => [
                [1, 2, 3, 4],
                [5, 6, 7],
                [1, 2, 3, 4, 5, 6, 7],
            ],
            'array with iterator' => [
                [1, 2, 3, 4],
                $this->createIterator(5, 6, 7),
                [1, 2, 3, 4, 5, 6, 7],
            ],
            'array with generator' => [
                [1, 2, 3, 4],
                $this->createGenerator(5, 6, 7),
                [1, 2, 3, 4, 5, 6, 7],
            ],
            'empty' => [
                [],
                [],
                [],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param callable(mixed, int=): bool $filter
     * @psalm-param array<int, mixed> $reference
     * @dataProvider provideFilterData
     */
    public function testFilter(iterable $input, callable $filter, array $reference): void
    {
        $result = FluentIterable::of($input)->filter($filter)->toArray();

        $this->assertSame($reference, $result);
    }

    public function provideFilterData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                fn (int $item): bool => $item >= 3,
                [3, 4],
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                fn (int $item): bool => $item >= 3,
                [3, 4],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $item): bool => $item >= 3,
                [3, 4],
            ],
            'callback with index' => [
                [1, 2, 3, 4],
                fn (int $item, int $index): bool => $index < 2,
                [1, 2],
            ],
            'empty input' => [
                [],
                fn (int $item): bool => $item >= 3,
                [],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param callable(mixed, int=): mixed $mapper
     * @psalm-param array<int, mixed> $reference
     * @dataProvider provideMapData
     */
    public function testMap(iterable $input, callable $mapper, array $reference): void
    {
        $result = FluentIterable::of($input)->map($mapper)->toArray();

        $this->assertSame($reference, $result);
    }

    public function provideMapData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                fn (int $item): int => $item + 1,
                [2, 3, 4, 5],
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                fn (int $item): int => $item + 1,
                [2, 3, 4, 5],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $item): int => $item + 1,
                [2, 3, 4, 5],
            ],
            'callback with index' => [
                [1, 2, 3, 4],
                fn (int $item, int $index): int => $item + $index,
                [1, 3, 5, 7],
            ],
            'empty input' => [
                [],
                fn (int $item): int => $item + 1,
                [],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param int $offset
     * @psalm-param array<int, mixed> $reference
     * @dataProvider provideSkipData
     */
    public function testSkip(iterable $input, int $offset, array $reference): void
    {
        $result = FluentIterable::of($input)->skip($offset)->toArray();

        $this->assertSame($reference, $result);
    }

    public function provideSkipData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                2,
                [3, 4],
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                2,
                [3, 4],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                2,
                [3, 4],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param int $limit
     * @psalm-param array<int, mixed> $reference
     * @dataProvider provideSliceData
     */
    public function testLimit(iterable $input, int $limit, array $reference): void
    {
        $result = FluentIterable::of($input)->limit($limit)->toArray();

        $this->assertSame($reference, $result);
    }

    public function provideSliceData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                2,
                [1, 2],
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                2,
                [1, 2],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                2,
                [1, 2],
            ],
            'limit is more then length' => [
                [1, 2, 3, 4],
                10,
                [1, 2, 3, 4],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param array<int, mixed> $reference
     * @dataProvider providePeek
     * @psalm-suppress MixedArrayAssignment
     */
    public function testPeek(iterable $input, array $reference): void
    {
        $result = [];
        FluentIterable::of($input)
            ->peek(
                function (mixed $item, int $key) use (&$result): void {
                    $result[$key] = $item;
                }
            )
            ->toArray();

        $this->assertSame($reference, $result);
    }

    public function providePeek(): array
    {
        return [
            'array' => [
                ['q', 'w', 'e'],
                ['q', 'w', 'e'],
            ],
            'empty array' => [
                [],
                [],
            ],
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'generator' => [
                $this->createGenerator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param callable(mixed, mixed): int $callback
     * @psalm-param array<int, mixed> $reference
     * @dataProvider provideSorted
     */
    public function testSorted(iterable $input, callable $callback, array $reference): void
    {
        $result = FluentIterable::of($input)->sorted($callback)->toArray();

        $this->assertSame($reference, $result);
    }

    public function provideSorted(): array
    {
        return [
            'array' => [
                [3, 1, 4, 2],
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4],
            ],
            'empty array' => [
                [],
                fn (int $a, int $b): int => $a <=> $b,
                [],
            ],
            'iterator' => [
                $this->createIterator(4, 3, 2, 1),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4],
            ],
        ];
    }

    /**
     * @psalm-param iterable<int, int> $input
     * @psalm-param array<int, int> $reference
     * @psalm-suppress MixedArrayAssignment
     * @dataProvider provideWalkData
     */
    public function testWalk(iterable $input, array $reference): void
    {
        $result = [];
        FluentIterable::of($input)->walk(
            function (int $item, int $key) use (&$result): void {
                $result[$key] = $item;
            }
        );

        $this->assertSame($reference, $result);
    }

    public function provideWalkData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                [1, 2, 3, 4],
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                [1, 2, 3, 4],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                [1, 2, 3, 4],
            ],
            'empty input' => [
                [],
                [],
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param callable(mixed, mixed, int=): mixed $reducer
     * @psalm-param mixed $initial
     * @psalm-param mixed $reference
     * @dataProvider provideReduceData
     */
    public function testReduce(iterable $input, callable $reducer, mixed $initial, mixed $reference): void
    {
        $result = FluentIterable::of($input)->reduce($reducer, $initial)->get();

        $this->assertSame($reference, $result);
    }

    public function provideReduceData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                fn (int $carry, int $item): int => $carry + $item,
                0,
                10,
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                fn (int $carry, int $item): int => $carry + $item,
                0,
                10,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $carry, int $item): int => $carry + $item,
                0,
                10,
            ],
            'reducer with index' => [
                [1, 2, 3, 4],
                fn (int $carry, int $item, int $index): int => $carry + $item + $index,
                0,
                16,
            ],
            'empty input' => [
                [],
                fn (int $carry, int $item): int => $carry + $item,
                123,
                123,
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param callable(mixed, mixed, int=): int $comparator
     * @psalm-param mixed $reference
     * @dataProvider provideMinData
     */
    public function testMin(iterable $input, callable $comparator, mixed $reference): void
    {
        $result = FluentIterable::of($input)->min($comparator)->get();

        $this->assertSame($reference, $result);
    }

    public function provideMinData(): array
    {
        return [
            'array' => [
                [4, 3, 2, 1, 7, 19],
                fn (int $o1, int $o2): int => $o1 <=> $o2,
                1,
            ],
            'if two items are equal then first should be returned' => [
                [[1, 1, 1], [2, 2, 2], [3, 3, 3, 3]],
                fn (array $o1, array $o2): int => \count($o1) <=> \count($o2),
                [1, 1, 1],
            ],
            'iterator' => [
                $this->createIterator(4, 3, 2, 1, 7, 19),
                fn (int $o1, int $o2): int => $o1 <=> $o2,
                1,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $o1, int $o2): int => $o1 <=> $o2,
                1,
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param callable(mixed, mixed, int=): int $comparator
     * @psalm-param mixed $reference
     * @dataProvider provideMaxData
     */
    public function testMax(iterable $input, callable $comparator, mixed $reference): void
    {
        $result = FluentIterable::of($input)->max($comparator)->get();

        $this->assertSame($reference, $result);
    }

    public function provideMaxData(): array
    {
        return [
            'array' => [
                [4, 3, 2, 1, 7, 19],
                fn (int $o1, int $o2): int => $o1 <=> $o2,
                19,
            ],
            'if two items are equal then first should be returned' => [
                [[1, 1, 1], [2, 2, 2], [3, 3]],
                fn (array $o1, array $o2): int => \count($o1) <=> \count($o2),
                [1, 1, 1],
            ],
            'iterator' => [
                $this->createIterator(4, 3, 22, 1, 7, 19),
                fn (int $o1, int $o2): int => $o1 <=> $o2,
                22,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $o1, int $o2): int => $o1 <=> $o2,
                4,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param callable(int, int=): bool $filter
     * @psalm-param int $orElse
     * @psalm-param mixed $reference
     * @dataProvider provideFindOneData
     */
    public function testFindOne(iterable $input, callable $filter, int $orElse, mixed $reference): void
    {
        $result = FluentIterable::of($input)->findOne($filter)->orElse($orElse);

        $this->assertSame($reference, $result);
    }

    public function provideFindOneData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                fn (int $item): bool => $item === 3,
                0,
                3,
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                fn (int $item): bool => $item === 3,
                0,
                3,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                fn (int $item): bool => $item === 3,
                0,
                3,
            ],
            'nothing found' => [
                [1, 2, 3, 4],
                fn (int $item): bool => $item === 12,
                123123,
                123123,
            ],
            'empty array' => [
                [],
                fn (int $item): bool => $item === 3,
                123123,
                123123,
            ],
            'if two items are equal then first should be returned' => [
                [1, 2, 3, 4],
                fn (int $item): bool => $item < 3,
                0,
                1,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param int $index
     * @psalm-param int $orElse
     * @psalm-param mixed $reference
     * @dataProvider provideFindByIndexData
     */
    public function testFindByIndex(iterable $input, int $index, int $orElse, mixed $reference): void
    {
        $result = FluentIterable::of($input)->findByIndex($index)->orElse($orElse);

        $this->assertSame($reference, $result);
    }

    public function provideFindByIndexData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                3,
                0,
                4,
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                2,
                0,
                3,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                1,
                0,
                2,
            ],
            'empty input' => [
                [],
                0,
                123,
                123,
            ],
            'check break in the loop' => [
                $this->createOneItemAndExceptionIterator(),
                1,
                0,
                1,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param int $orElse
     * @psalm-param mixed $reference
     * @dataProvider provideFindFirstData
     */
    public function testFindFirst(iterable $input, int $orElse, mixed $reference): void
    {
        $result = FluentIterable::of($input)->findFirst()->orElse($orElse);

        $this->assertSame($reference, $result);
    }

    public function provideFindFirstData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                0,
                1,
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                0,
                1,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                0,
                1,
            ],
            'empty input' => [
                [],
                123,
                123,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param int $orElse
     * @psalm-param mixed $reference
     * @dataProvider provideFindLastData
     */
    public function testFindLast(iterable $input, int $orElse, mixed $reference): void
    {
        $result = FluentIterable::of($input)->findLast()->orElse($orElse);

        $this->assertSame($reference, $result);
    }

    public function provideFindLastData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                0,
                4,
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                0,
                4,
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                0,
                4,
            ],
            'empty input' => [
                [],
                123,
                123,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param callable(int, int=): bool $callback
     * @psalm-param bool $reference
     * @dataProvider provideMatchAll
     */
    public function testMatchAll(iterable $input, callable $callback, bool $reference): void
    {
        $result = FluentIterable::of($input)->matchAll($callback);

        $this->assertSame($reference, $result);
    }

    public function provideMatchAll(): array
    {
        return [
            'match all' => [
                [1, 2, 3, 4],
                fn (int $val): bool => $val < 5,
                true,
            ],
            'not all match' => [
                [1, 2, 3, 4],
                fn (int $val): bool => $val > 2,
                false,
            ],
            'empty' => [
                [],
                fn (int $val): bool => $val > 2,
                true,
            ],
            'match all with index' => [
                [0, 1, 2, 3],
                fn (int $val, int $index): bool => $val === $index,
                true,
            ],
            'check break in the loop' => [
                $this->createOneItemAndExceptionIterator(),
                fn (int $val): bool => $val > 10,
                false,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param callable(int, int=): bool $callback
     * @psalm-param bool $reference
     * @dataProvider provideMatchAny
     */
    public function testMatchAny(iterable $input, callable $callback, bool $reference): void
    {
        $result = FluentIterable::of($input)->matchAny($callback);

        $this->assertSame($reference, $result);
    }

    public function provideMatchAny(): array
    {
        return [
            'match any' => [
                [1, 2, 3, 4],
                fn (int $val): bool => $val < 2,
                true,
            ],
            'nothing match' => [
                [1, 2, 3, 4],
                fn (int $val): bool => $val > 10,
                false,
            ],
            'empty' => [
                [],
                fn (int $val): bool => $val > 2,
                false,
            ],
            'match any with index' => [
                [0, 1, 2, 3],
                fn (int $val, int $index): bool => $val === $index,
                true,
            ],
            'check break in the loop' => [
                $this->createOneItemAndExceptionIterator(),
                fn (int $val): bool => $val < 10,
                true,
            ],
        ];
    }

    /**
     * @psalm-param iterable<int> $input
     * @psalm-param callable(int, int=): bool $callback
     * @psalm-param bool $reference
     * @dataProvider provideMatchNone
     */
    public function testMatchNone(iterable $input, callable $callback, bool $reference): void
    {
        $result = FluentIterable::of($input)->matchNone($callback);

        $this->assertSame($reference, $result);
    }

    public function provideMatchNone(): array
    {
        return [
            'match none' => [
                [1, 2, 3, 4],
                fn (int $val): bool => $val > 10,
                true,
            ],
            'match any' => [
                [1, 2, 3, 4],
                fn (int $val): bool => $val < 2,
                false,
            ],
            'empty' => [
                [],
                fn (int $val): bool => $val > 2,
                true,
            ],
            'match any with index' => [
                [0, 1, 2, 3],
                fn (int $val, int $index): bool => $val === $index,
                false,
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param mixed $count
     * @dataProvider provideCountData
     */
    public function testCount(iterable $input, int $count): void
    {
        $result = FluentIterable::of($input)->count();

        $this->assertSame($count, $result);
    }

    public function provideCountData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                4,
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3),
                3,
            ],
            'generator' => [
                $this->createGenerator(1, 4),
                2,
            ],
            'empty input' => [
                [],
                0,
            ],
            'countable iterator' => [
                $this->createCountableIterator(10),
                10,
            ],
        ];
    }

    /**
     * @psalm-param iterable $input
     * @psalm-param array $reference
     * @dataProvider provideGetIteratorData
     */
    public function testGetIterator(iterable $input, array $reference): void
    {
        $iterator = FluentIterable::of($input)->getIterator();

        $result = [];
        foreach ($iterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideGetIteratorData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                [1, 2, 3, 4],
            ],
            'iterator' => [
                $this->createIterator(1, 2, 3, 4),
                [1, 2, 3, 4],
            ],
            'generator' => [
                $this->createGenerator(1, 2, 3, 4),
                [1, 2, 3, 4],
            ],
            'empty input' => [
                [],
                [],
            ],
        ];
    }

    public function testFluent(): void
    {
        $input = $this->createGenerator(1, 2, 3, 4, 5, 6, 7);

        $result = FluentIterable::of($input)
            ->skip(3)
            ->filter(fn (int $item): bool => $item > 2)
            ->filter(fn (int $item): bool => $item < 7)
            ->limit(2)
            ->map(fn (int $item): int => $item + 1)
            ->map(fn (int $item): int => $item + 1)
            ->toArray();

        $this->assertSame([6, 7], $result);
    }
}
