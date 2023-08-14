<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Type defined library wrapper for CallbackFilterIterator.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 *
 * @internal
 */
final class CallbackFilterIterator implements \Countable, \Iterator
{
    private readonly \CallbackFilterIterator $iterator;

    private int $count = 0;

    /**
     * @param \Iterator<int, TValue> $iterator
     *
     * @psalm-param callable(TValue, int=): bool $callback
     */
    public function __construct(\Iterator $iterator, callable $callback)
    {
        $filterCallback = function (mixed $current, int $key, \Iterator $iterator) use ($callback): bool {
            /** @psalm-var TValue */
            $item = $current;

            return \call_user_func($callback, $item, $key);
        };

        $this->iterator = new \CallbackFilterIterator($iterator, $filterCallback);
    }

    /**
     * @return TValue
     */
    public function current(): mixed
    {
        /** @psalm-var TValue */
        $current = $this->iterator->current();

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
        return IteratorHelper::count($this->iterator->getInnerIterator());
    }
}
