<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\Iterator\AnySourceIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
class AnySourceIteratorTest extends IteratorCase
{
    /**
     * @dataProvider provideIteratorData
     */
    public function testOf(iterable $input, array $reference): void
    {
        $iterator = AnySourceIterator::of($input);

        $this->assertIteratorContains($reference, $iterator);
    }

    /**
     * @dataProvider provideIteratorData
     */
    public function testIterator(iterable $input, array $reference): void
    {
        $iterator = AnySourceIterator::of($input);

        $this->assertIteratorContains($reference, $iterator);
    }

    public function provideIteratorData(): array
    {
        return [
            'array' => [
                ['q', 'w', 'e', 1, 2, 3],
                ['q', 'w', 'e', 1, 2, 3],
            ],
            'string keys array' => [
                ['key' => 0, 'key 1' => 1],
                [0, 1],
            ],
            'nested array' => [
                [1, [2, 3]],
                [1, [2, 3]],
            ],
            'empty array' => [
                [],
                [],
            ],
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'iterator aggregate' => [
                $this->createIteratorAggregate('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                $this->createEmptyIterator(),
                [],
            ],
            'generator' => [
                $this->createGenerator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
        ];
    }

    /**
     * @dataProvider provideCountData
     */
    public function testCount(iterable $input, int $reference): void
    {
        $iterator = AnySourceIterator::of($input);

        $this->assertCountableCount($reference, $iterator);
    }

    public function provideCountData(): array
    {
        return [
            'array' => [
                ['q', 'w', 'e'],
                3,
            ],
            'empty array' => [
                [],
                0,
            ],
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                3,
            ],
            'iterator aggregate' => [
                $this->createIteratorAggregate('q', 'w', 'e'),
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
