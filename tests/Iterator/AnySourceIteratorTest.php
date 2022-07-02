<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
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
        $immutableIterator = new AnySourceIterator($input);

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
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
}
