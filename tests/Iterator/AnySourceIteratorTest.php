<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
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
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                ['q', 'w', 'e'],
            ],
            'iterator aggregate' => [
                new ArrayObject(['q', 'w', 'e']),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                (new ArrayObject([]))->getIterator(),
                [],
            ],
            'generator' => [
                (function () {
                    yield 'q';
                    yield 'w';
                    yield 'e';
                })(),
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
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                3,
            ],
            'iterator aggregate' => [
                new ArrayObject(['q', 'w', 'e']),
                3,
            ],
            'generator' => [
                (function () {yield 'q'; })(),
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
