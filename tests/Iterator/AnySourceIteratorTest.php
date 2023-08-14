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

    public static function provideIteratorData(): array
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
                self::createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'iterator aggregate' => [
                self::createIteratorAggregate('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                [],
            ],
            'generator' => [
                self::createGenerator('q', 'w', 'e'),
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

    public static function provideCountData(): array
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
                self::createIterator('q', 'w', 'e'),
                3,
            ],
            'iterator aggregate' => [
                self::createIteratorAggregate('q', 'w', 'e'),
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
