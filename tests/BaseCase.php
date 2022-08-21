<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use ArrayObject;
use Generator;
use Iterator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Base test case for all tests.
 */
abstract class BaseCase extends TestCase
{
    protected function createIteratorAggregate(mixed ...$data): IteratorAggregate
    {
        return new ArrayObject($data);
    }

    protected function createIterator(mixed ...$data): Iterator
    {
        return (new ArrayObject($data))->getIterator();
    }

    protected function createIteratorFromArray(array $data): Iterator
    {
        return (new ArrayObject($data))->getIterator();
    }

    protected function createEmptyIterator(): Iterator
    {
        return (new ArrayObject([]))->getIterator();
    }

    protected function createOneItemAndExceptionIterator(): Iterator
    {
        return new class() implements Iterator {
            private int $counter = 0;

            public function current(): mixed
            {
                return $this->counter;
            }

            public function key(): int
            {
                return $this->counter;
            }

            public function next(): void
            {
                ++$this->counter;
            }

            public function rewind(): void
            {
                $this->counter = 0;
            }

            public function valid(): bool
            {
                if ($this->counter > 1) {
                    throw new RuntimeException("Can't iterate over 3");
                }

                return true;
            }
        };
    }

    protected function createGenerator(mixed ...$data): Generator
    {
        return (function () use ($data) {
            foreach ($data as $item) {
                yield $item;
            }
        })();
    }
}
