<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\DistinctIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
class DistinctIteratorTest extends IteratorCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param mixed $reference
     *
     * @dataProvider provideIterator
     */
    public function testIterator(Iterator $iterator, mixed $reference): void
    {
        $iterator = new DistinctIterator($iterator);

        $this->assertIteratorContains($reference, $iterator);
    }

    public function provideIterator(): array
    {
        return [
            'only distinct items' => [
                $this->createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'some duplicated items' => [
                $this->createIterator('q', 'q', 'q', 'w', 'w', 'w', 'w', 'e', 'r', 'r'),
                ['q', 'w', 'e', 'r'],
            ],
            'duplicated items in the different places' => [
                $this->createIterator('q', 'w', 'e', 'w', 'r', 'r', 'w', 'w', 'w', 'q', 'q'),
                ['q', 'w', 'e', 'r'],
            ],
            'mixed items' => [
                $this->createIterator(1, 1, 'w', 'w', 'w', 'w', 2, 2, 'r', 'r'),
                [1, 'w', 2, 'r'],
            ],
            'no items' => [
                $this->createEmptyIterator(),
                [],
            ],
            'same mixed items of different types' => [
                $this->createIterator(1, '1', 2),
                [1, '1', 2],
            ],
            'array items' => [
                $this->createIterator([1, 'q'], [2, 2], [1, 'q'], ['z' => 1, 'x' => 'q'], ['q', 1]),
                [[1, 'q'], [2, 2], ['z' => 1, 'x' => 'q'], ['q', 1]],
            ],
        ];
    }

    /**
     * @dataProvider provideCount
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $iterator = new DistinctIterator($input);

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
