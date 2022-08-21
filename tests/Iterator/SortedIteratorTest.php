<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\SortedIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class SortedIteratorTest extends BaseCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed, mixed): int $callback
     * @psalm-param mixed[] $reference
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, callable $callback, array $reference): void
    {
        $iterator = new SortedIterator($iterator, $callback);

        $result = [];
        foreach ($iterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideIteratorData(): array
    {
        return [
            'iterator' => [
                $this->createIterator(5, 3, 1, 2, 4),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4, 5],
            ],
            'empty iterator' => [
                $this->createEmptyIterator(),
                fn (int $a, int $b): int => $a <=> $b,
                [],
            ],
            'generator' => [
                $this->createGenerator(3, 1, 2, 4),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4],
            ],
        ];
    }

    public function testCurrentInitializeArray(): void
    {
        $input = $this->createIterator(5, 3, 1, 2, 4);
        $iterator = new SortedIterator($input, fn (int $a, int $b): int => $a <=> $b);

        $result = $iterator->current();

        $this->assertSame(1, $result);
    }
}
