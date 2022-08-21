<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Countable;
use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class CallbackMapIteratorTest extends BaseCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed): mixed $callback
     * @psalm-param mixed $reference
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, callable $callback, mixed $reference): void
    {
        $immutableIterator = new CallbackMapIterator($iterator, $callback);

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideIteratorData(): array
    {
        return [
            'simple iterator' => [
                $this->createIterator('q', 'w', 'e'),
                fn (string $letter): string => "{$letter}a",
                ['qa', 'wa', 'ea'],
            ],
        ];
    }

    /**
     * @dataProvider provideCountData
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $immutableIterator = new CallbackMapIterator($input, fn () => false);

        $result = \count($immutableIterator);

        $this->assertSame($reference, $result);
    }

    public function provideCountData(): array
    {
        return [
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
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
