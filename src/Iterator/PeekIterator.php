<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Additionally performing the provided action on each element as elements are consumed from the resulting iterator.
 * It's useful for debugging.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 *
 * @internal
 */
final class PeekIterator implements \Countable, \Iterator
{
    /**
     * @var \Iterator<mixed, TValue>
     */
    private readonly \Iterator $iterator;

    /**
     * @var callable
     *
     * @psalm-var callable(TValue, int=): void
     */
    private readonly mixed $callback;

    private int $count = 0;

    /**
     * @param \Iterator<mixed, TValue> $iterator
     *
     * @psalm-param callable(TValue, int=): void $callback
     */
    public function __construct(\Iterator $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * @return TValue
     *
     * @psalm-suppress PossiblyNullArgument
     *
     * @TODO seems like a Psalm bug. Check on next version
     */
    #[\Override]
    public function current(): mixed
    {
        $current = $this->iterator->current();

        \call_user_func($this->callback, $current, $this->count);

        return $current;
    }

    #[\Override]
    public function key(): int
    {
        return $this->count;
    }

    #[\Override]
    public function next(): void
    {
        ++$this->count;
        $this->iterator->next();
    }

    #[\Override]
    public function rewind(): void
    {
        $this->count = 0;
        $this->iterator->rewind();
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    #[\Override]
    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }
}
