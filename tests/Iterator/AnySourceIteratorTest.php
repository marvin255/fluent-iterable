<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Countable;
use Generator;
use Iterator;
use Marvin255\FluentIterable\Iterator\AnySourceIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class AnySourceIteratorTest extends BaseCase
{
    /**
     * @dataProvider provideIteratorData
     */
    public function testOf(iterable $input, array $reference): void
    {
        $immutableIterator = AnySourceIterator::of($input);

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    /**
     * @dataProvider provideIteratorData
     */
    public function testIterator(iterable $input, array $reference): void
    {
        $immutableIterator = AnySourceIterator::of($input);

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        if (!($input instanceof Generator)) {
            $result = [];
            foreach ($immutableIterator as $key => $item) {
                $result[$key] = $item;
            }
        }

        $this->assertSame($reference, $result);
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
        $immutableIterator = AnySourceIterator::of($input);

        $result = \count($immutableIterator);

        $this->assertSame($reference, $result);
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
                new class() implements Countable, Iterator {
                    public function current(): mixed
                    {
                        return 1;
                    }

                    public function key(): int
                    {
                        return 1;
                    }

                    public function next(): void
                    {
                    }

                    public function rewind(): void
                    {
                    }

                    public function valid(): bool
                    {
                        return false;
                    }

                    public function count(): int
                    {
                        return 2;
                    }
                },
                2,
            ],
        ];
    }
}
