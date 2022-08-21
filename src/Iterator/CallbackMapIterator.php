<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Countable;
use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that converts every item using callback function.
 * All keys will be converted to ints.
 *
 * @template TValue
 * @template TConverted
 * @implements Iterator<int, TConverted>
 */
final class CallbackMapIterator implements Countable, Iterator
{
    /**
     * @var Iterator<mixed, TValue>
     */
    private readonly Iterator $iterator;

    /**
     * @var callable
     * @psalm-var callable(TValue, int=): mixed
     */
    private readonly mixed $callback;

    private int $count = 0;

    /**
     * @param Iterator<mixed, TValue> $iterator
     * @param callable                $callback
     * @psalm-param callable(TValue, int=): TConverted $callback
     */
    public function __construct(Iterator $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * @return TConverted
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

    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }
}
