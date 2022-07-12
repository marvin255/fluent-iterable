<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
use Generator;
use InvalidArgumentException;
use Iterator;
use Marvin255\FluentIterable\Iterator\SliceIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class SliceIteratorTest extends BaseCase
{
    public function testConstructNegativeOffset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SliceIterator(
            (new ArrayObject([]))->getIterator(),
            -1,
            10
        );
    }

    public function testConstructNegativeLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SliceIterator(
            (new ArrayObject([]))->getIterator(),
            null,
            -10
        );
    }

    public function testConstructZeroLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SliceIterator(
            (new ArrayObject([]))->getIterator(),
            10,
            0
        );
    }

    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param int|null $from
     * @psalm-param int|null $to
     * @psalm-param mixed $reference
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, ?int $from, ?int $to, mixed $reference): void
    {
        $immutableIterator = new SliceIterator($iterator, $from, $to);

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        if (!($iterator instanceof Generator)) {
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
            'iterator' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                1,
                2,
                ['w', 'e'],
            ],
            'iterator null offset' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                null,
                2,
                ['q', 'w'],
            ],
            'iterator null length' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                2,
                null,
                ['e', 'r'],
            ],
            'iterator offset greater than length' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                10,
                15,
                [],
            ],
            'iterator get one element' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                2,
                1,
                ['e'],
            ],
            'iterator get first element' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                0,
                1,
                ['q'],
            ],
            'iterator length more that real' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                2,
                1000,
                ['e', 'r'],
            ],
            'generator' => [
                (function () {
                    yield 'q';
                    yield 'w';
                    yield 'e';
                    yield 'r';
                })(),
                1,
                2,
                ['w', 'e'],
            ],
        ];
    }
}
