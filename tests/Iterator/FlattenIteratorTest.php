<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\FlattenIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
class FlattenIteratorTest extends IteratorCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed, int=): iterable<mixed> $callback
     * @psalm-param array $reference
     *
     * @dataProvider provideIterator
     */
    public function testIterator(Iterator $iterator, callable $callback, array $reference): void
    {
        $iterator = new FlattenIterator($iterator, $callback);

        $this->assertIteratorContains($reference, $iterator);
    }

    public function provideIterator(): array
    {
        return [
            'nested arrays' => [
                $this->createIterator([5, 6], [3, 4], [1, 2]),
                fn (array $item): iterable => $item,
                [5, 6, 3, 4, 1, 2],
            ],
            'nested arrays with different sizes' => [
                $this->createIterator([1, 2], [3, 4, 5, 6], [7]),
                fn (array $item): iterable => $item,
                [1, 2, 3, 4, 5, 6, 7],
            ],
            'nested with empty items' => [
                $this->createIterator([], [], [1, 2], [], [], [], [3, 4], [], []),
                fn (array $item): iterable => $item,
                [1, 2, 3, 4],
            ],
            'empty' => [
                $this->createEmptyIterator(),
                fn (array $item): iterable => $item,
                [],
            ],
            'multiple empty items' => [
                $this->createIterator([], [], [], [], [], [], []),
                fn (array $item): iterable => $item,
                [],
            ],
        ];
    }

    /**
     * @dataProvider provideCount
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $iterator = new FlattenIterator($input, fn (mixed $item): iterable => [$item]);

        $this->assertCountableCount($reference, $iterator);
    }

    public function provideCount(): array
    {
        return [
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                3,
            ],
            'generator' => [
                $this->createGenerator('q'),
                1,
            ],
            'countable only iterator' => [
                $this->createCountableIterator(2),
                2,
            ],
        ];
    }
}
