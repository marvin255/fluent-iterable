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
    public function testIterator(\Iterator $iterator, callable $callback, array $reference): void
    {
        $iterator = new FlattenIterator($iterator, $callback);

        $this->assertIteratorContains($reference, $iterator);
    }

    public static function provideIterator(): array
    {
        return [
            'nested arrays' => [
                self::createIterator([5, 6], [3, 4], [1, 2]),
                fn (array $item): iterable => $item,
                [5, 6, 3, 4, 1, 2],
            ],
            'nested arrays with different sizes' => [
                self::createIterator([1, 2], [3, 4, 5, 6], [7]),
                fn (array $item): iterable => $item,
                [1, 2, 3, 4, 5, 6, 7],
            ],
            'nested with empty items' => [
                self::createIterator([], [], [1, 2], [], [], [], [3, 4], [], []),
                fn (array $item): iterable => $item,
                [1, 2, 3, 4],
            ],
            'empty' => [
                self::createEmptyIterator(),
                fn (array $item): iterable => $item,
                [],
            ],
            'multiple empty items' => [
                self::createIterator([], [], [], [], [], [], []),
                fn (array $item): iterable => $item,
                [],
            ],
        ];
    }

    /**
     * @dataProvider provideCount
     */
    public function testCount(\Iterator $input, int $reference): void
    {
        $iterator = new FlattenIterator($input, fn (mixed $item): iterable => [$item]);

        $this->assertCountableCount($reference, $iterator);
    }

    public static function provideCount(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                3,
            ],
            'generator' => [
                self::createGenerator('q'),
                1,
            ],
            'countable only iterator' => [
                self::createCountableIterator(2),
                2,
            ],
        ];
    }
}
