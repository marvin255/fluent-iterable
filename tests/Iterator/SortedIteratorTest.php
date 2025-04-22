<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\SortedIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
final class SortedIteratorTest extends IteratorCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed, mixed): int $callback
     * @psalm-param mixed[] $reference
     *
     * @dataProvider provideIteratorData
     */
    public function testIterator(\Iterator $iterator, callable $callback, array $reference): void
    {
        $iterator = new SortedIterator($iterator, $callback);

        $this->assertIteratorContains($reference, $iterator);
    }

    public static function provideIteratorData(): array
    {
        return [
            'iterator' => [
                self::createIterator(5, 3, 1, 2, 4),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4, 5],
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                fn (int $a, int $b): int => $a <=> $b,
                [],
            ],
            'generator' => [
                self::createGenerator(3, 1, 2, 4),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4],
            ],
        ];
    }

    public function testCurrentInitializeArray(): void
    {
        $input = self::createIterator(5, 3, 1, 2, 4);
        $iterator = new SortedIterator($input, fn (int $a, int $b): int => $a <=> $b);

        $result = $iterator->current();

        $this->assertSame(1, $result);
    }
}
