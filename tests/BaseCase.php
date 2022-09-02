<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use ArrayObject;
use Countable;
use Generator;
use Iterator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Base test case for all tests.
 *
 * Implements some most using methods.
 */
abstract class BaseCase extends TestCase
{
    /**
     * Creates IteratorAggregate instance using set parameters.
     */
    protected function createIteratorAggregate(mixed ...$data): IteratorAggregate
    {
        return new ArrayObject($data);
    }

    /**
     * Creates Iterator instance using set parameters.
     */
    protected function createIterator(mixed ...$data): Iterator
    {
        return (new ArrayObject($data))->getIterator();
    }

    /**
     * Creates Iterator instance from items of the set array.
     */
    protected function createIteratorFromArray(array $data): Iterator
    {
        return (new ArrayObject($data))->getIterator();
    }

    /**
     * Creates Iterator instance with no items.
     */
    protected function createEmptyIterator(): Iterator
    {
        return (new ArrayObject([]))->getIterator();
    }

    /**
     * Creates Iterator which throws exception on the second iteration.
     */
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

    /**
     * Creates Iterator which implementing Countable and returns set int as count.
     */
    protected function createCountableIterator(int $count): Iterator
    {
        return new class($count) implements Countable, Iterator {
            private int $count;

            public function __construct(int $count)
            {
                $this->count = $count;
            }

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
                return $this->count;
            }
        };
    }

    /**
     * Creates Generator instance using set parameters.
     */
    protected function createGenerator(mixed ...$data): Generator
    {
        return (function () use ($data) {
            foreach ($data as $item) {
                yield $item;
            }
        })();
    }
}
