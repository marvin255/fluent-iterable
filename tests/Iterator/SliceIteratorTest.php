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
    public function testConstructNegativeFrom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SliceIterator(
            (new ArrayObject([]))->getIterator(),
            -1,
            10
        );
    }

    public function testConstructNegativeTo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SliceIterator(
            (new ArrayObject([]))->getIterator(),
            null,
            -10
        );
    }

    public function testConstructNegativeFromGreaterThanTo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SliceIterator(
            (new ArrayObject([]))->getIterator(),
            10,
            1
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
            'iterator null from' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                null,
                2,
                ['q', 'w', 'e'],
            ],
            'iterator null to' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                2,
                null,
                ['e', 'r'],
            ],
            'iterator from greater than length' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                10,
                15,
                [],
            ],
            'iterator from equals to' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                2,
                2,
                ['e'],
            ],
            'iterator from equals to and equals zero' => [
                (new ArrayObject(['q', 'w', 'e', 'r']))->getIterator(),
                0,
                0,
                ['q'],
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
