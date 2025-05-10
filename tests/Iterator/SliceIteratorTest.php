<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\FluentIterableException;
use Marvin255\FluentIterable\Iterator\SliceIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
final class SliceIteratorTest extends IteratorCase
{
    public function testConstructNegativeOffset(): void
    {
        $this->expectException(FluentIterableException::class);
        new SliceIterator(
            self::createEmptyIterator(),
            -1,
            10
        );
    }

    public function testConstructNegativeLength(): void
    {
        $this->expectException(FluentIterableException::class);
        new SliceIterator(
            self::createEmptyIterator(),
            null,
            -10
        );
    }

    public function testConstructZeroLength(): void
    {
        $this->expectException(FluentIterableException::class);
        new SliceIterator(
            self::createEmptyIterator(),
            10,
            0
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideIteratorData')]
    public function testIterator(\Iterator $iterator, ?int $from, ?int $to, array $reference): void
    {
        $sliceIterator = new SliceIterator($iterator, $from, $to);

        $this->assertIteratorContains($reference, $sliceIterator);
    }

    public static function provideIteratorData(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e', 'r'),
                1,
                2,
                ['w', 'e'],
            ],
            'iterator null offset' => [
                self::createIterator('q', 'w', 'e', 'r'),
                null,
                2,
                ['q', 'w'],
            ],
            'iterator null length' => [
                self::createIterator('q', 'w', 'e', 'r'),
                2,
                null,
                ['e', 'r'],
            ],
            'iterator offset greater than length' => [
                self::createIterator('q', 'w', 'e', 'r'),
                10,
                15,
                [],
            ],
            'iterator get one element' => [
                self::createIterator('q', 'w', 'e', 'r'),
                2,
                1,
                ['e'],
            ],
            'iterator get first element' => [
                self::createIterator('q', 'w', 'e', 'r'),
                0,
                1,
                ['q'],
            ],
            'iterator length more than real' => [
                self::createIterator('q', 'w', 'e', 'r'),
                2,
                1000,
                ['e', 'r'],
            ],
        ];
    }
}
