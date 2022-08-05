<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
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
                (new ArrayObject([5, 3, 1, 2, 4]))->getIterator(),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4, 5],
            ],
            'empty iterator' => [
                (new ArrayObject([]))->getIterator(),
                fn (int $a, int $b): int => $a <=> $b,
                [],
            ],
            'generator' => [
                (function () {
                    yield 3;
                    yield 1;
                    yield 2;
                    yield 4;
                })(),
                fn (int $a, int $b): int => $a <=> $b,
                [1, 2, 3, 4],
            ],
        ];
    }

    public function testCurrentInitializeArray(): void
    {
        $input = (new ArrayObject([5, 3, 1, 2, 4]))->getIterator();
        $iterator = new SortedIterator($input, fn (int $a, int $b): int => $a <=> $b);

        $result = $iterator->current();

        $this->assertSame(1, $result);
    }
}
