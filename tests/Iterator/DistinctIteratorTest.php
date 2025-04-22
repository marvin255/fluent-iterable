<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\Iterator\DistinctIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
final class DistinctIteratorTest extends IteratorCase
{
    /**
     * @dataProvider provideIterator
     */
    public function testIterator(\Iterator $iterator, array $reference): void
    {
        $iterator = new DistinctIterator($iterator);

        $this->assertIteratorContains($reference, $iterator);
    }

    public static function provideIterator(): array
    {
        return [
            'only distinct items' => [
                self::createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'some duplicated items' => [
                self::createIterator('q', 'q', 'q', 'w', 'w', 'w', 'w', 'e', 'r', 'r'),
                ['q', 'w', 'e', 'r'],
            ],
            'duplicated items in the different places' => [
                self::createIterator('q', 'w', 'e', 'w', 'r', 'r', 'w', 'w', 'w', 'q', 'q'),
                ['q', 'w', 'e', 'r'],
            ],
            'mixed items' => [
                self::createIterator(1, 1, 'w', 'w', 'w', 'w', 2, 2, 'r', 'r'),
                [1, 'w', 2, 'r'],
            ],
            'no items' => [
                self::createEmptyIterator(),
                [],
            ],
            'same mixed items of different types' => [
                self::createIterator(1, '1', 2),
                [1, '1', 2],
            ],
            'array items' => [
                self::createIterator([1, 'q'], [2, 2], [1, 'q'], ['z' => 1, 'x' => 'q'], ['q', 1]),
                [[1, 'q'], [2, 2], ['z' => 1, 'x' => 'q'], ['q', 1]],
            ],
        ];
    }

    /**
     * @dataProvider provideCount
     */
    public function testCount(\Iterator $input, int $reference): void
    {
        $iterator = new DistinctIterator($input);

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
