<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;

/**
 * Iterator that converts every item using callback function.
 * All keys will be converted to ints.
 *
 * @psalm-template TValue
 * @psalm-template TConverted
 * @implements Iterator<int, TConverted>
 */
class CallbackMapIterator implements Iterator
{
    /**
     * @psalm-var Iterator<mixed, TValue>
     */
    private readonly Iterator $iterator;

    /**
     * @psalm-var callable(TValue, int=): mixed
     */
    private readonly mixed $callback;

    private int $count = 0;

    /**
     * @psalm-param Iterator<mixed, TValue> $iterator
     * @psalm-param callable(TValue, int=): TConverted $callback
     */
    public function __construct(Iterator $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * @psalm-return TConverted
     */
    public function current(): mixed
    {
        /** @psalm-var TConverted */
        $res = \call_user_func($this->callback, $this->iterator->current(), $this->count);

        return $res;
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
}
