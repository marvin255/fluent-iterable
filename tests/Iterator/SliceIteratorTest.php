<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\Iterator\SliceIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
class SliceIteratorTest extends IteratorCase
{
    public function testConstructNegativeOffset(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new SliceIterator(
            $this->createEmptyIterator(),
            -1,
            10
        );
    }

    public function testConstructNegativeLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new SliceIterator(
            $this->createEmptyIterator(),
            null,
            -10
        );
    }

    public function testConstructZeroLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new SliceIterator(
            $this->createEmptyIterator(),
            10,
            0
        );
    }

    /**
     * @dataProvider provideIteratorData
     */
    public function testIterator(\Iterator $iterator, ?int $from, ?int $to, array $reference): void
    {
        $sliceIterator = new SliceIterator($iterator, $from, $to);

        $this->assertIteratorContains($reference, $sliceIterator);
    }

    public function provideIteratorData(): array
    {
        return [
            'iterator' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                1,
                2,
                ['w', 'e'],
            ],
            'iterator null offset' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                null,
                2,
                ['q', 'w'],
            ],
            'iterator null length' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                2,
                null,
                ['e', 'r'],
            ],
            'iterator offset greater than length' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                10,
                15,
                [],
            ],
            'iterator get one element' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                2,
                1,
                ['e'],
            ],
            'iterator get first element' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                0,
                1,
                ['q'],
            ],
            'iterator length more than real' => [
                $this->createIterator('q', 'w', 'e', 'r'),
                2,
                1000,
                ['e', 'r'],
            ],
        ];
    }
}
