<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use ArrayObject;
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
                (new ArrayObject([5, 6, 7]))->getIterator(),
                [1, 2, 3, 4, 5, 6, 7],
            ],
            'array with generator' => [
                [1, 2, 3, 4],
                (function () {
                    yield 5;
                    yield 6;
                    yield 7;
                })(),
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                fn (int $item): bool => $item >= 3,
                [3, 4],
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                fn (int $item): int => $item + 1,
                [2, 3, 4, 5],
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                2,
                [3, 4],
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
                2,
                [3, 4],
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                [1, 2, 3, 4],
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                fn (int $carry, int $item): int => $carry + $item,
                0,
                10,
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                0,
                1,
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
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
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                0,
                4,
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
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
     * @psalm-param iterable $input
     * @psalm-param array $reference
     * @dataProvider provideToIteratorData
     */
    public function testToIterator(iterable $input, array $reference): void
    {
        $iterator = FluentIterable::of($input)->toIterator();

        $result = [];
        foreach ($iterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideToIteratorData(): array
    {
        return [
            'array' => [
                [1, 2, 3, 4],
                [1, 2, 3, 4],
            ],
            'iterator' => [
                (new ArrayObject([1, 2, 3, 4]))->getIterator(),
                [1, 2, 3, 4],
            ],
            'generator' => [
                (function () {
                    yield 1;
                    yield 2;
                    yield 3;
                    yield 4;
                })(),
                [1, 2, 3, 4],
            ],
            'empty input' => [
                [],
                [],
            ],
        ];
    }
}
