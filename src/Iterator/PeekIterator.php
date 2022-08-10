<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Countable;
use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Additionally performing the provided action on each element as elements are consumed from the resulting iterator.
 * It's useful for debugging.
 *
 * @psalm-template TValue
 * @implements Iterator<int, TValue>
 */
final class PeekIterator implements Countable, Iterator
{
    /**
     * @psalm-var Iterator<mixed, TValue>
     */
    private readonly Iterator $iterator;

    /**
     * @psalm-var callable(TValue, int=): void
     */
    private readonly mixed $callback;

    private int $count = 0;

    /**
     * @psalm-param Iterator<mixed, TValue> $iterator
     * @psalm-param callable(TValue, int=): void $callback
     */
    public function __construct(Iterator $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * @psalm-return TValue
     */
    public function current(): mixed
    {
        $current = $this->iterator->current();

        \call_user_func($this->callback, $current, $this->count);

        return $current;
    }

    public function key(): int
    {
        return $this->count;
    }

    public function next(): void
    {
        ++$this->count;
        $this->iterator->next();
    }

    public function rewind(): void
    {
        $this->count = 0;
        $this->iterator->rewind();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }
}
