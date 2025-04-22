<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that accepts other iterator and callable.
 * It converts each item from nested iterator with callable and returns new iterable.
 *
 * @template TValue
 * @template TConverted
 *
 * @implements Iterator<int, TConverted>
 *
 * @internal
 */
final class FlattenIterator implements \Countable, \Iterator
{
    /**
     * @var \Iterator<mixed, TValue>
     */
    private readonly \Iterator $iterator;

    /**
     * @var callable
     *
     * @psalm-var callable(TValue, int=): iterable<TConverted>
     */
    private readonly mixed $callback;

    private int $count = 0;

    /**
     * @var array<int, TConverted>|null
     */
    private ?array $buffer = null;

    private int $bufferCount = 0;

    /**
     * @param \Iterator<mixed, TValue> $iterator
     *
     * @psalm-param callable(TValue, int=): iterable<TConverted> $callback
     */
    public function __construct(\Iterator $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * @return TConverted
     *
     * @psalm-suppress PossiblyNullArrayAccess
     */
    #[\Override]
    public function current(): mixed
    {
        /** @var TConverted */
        $current = $this->buffer[$this->bufferCount];

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
        ++$this->bufferCount;
    }

    #[\Override]
    public function rewind(): void
    {
        $this->count = 0;
        $this->iterator->rewind();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     *
     * @TODO seems like a Psalm bug. Check on next version
     */
    #[\Override]
    public function valid(): bool
    {
        if (null !== $this->buffer && $this->bufferCount < \count($this->buffer)) {
            return true;
        }

        if (null !== $this->buffer) {
            $this->iterator->next();
        }

        $this->buffer = null;
        $this->bufferCount = 0;
        while (null === $this->buffer && $this->iterator->valid()) {
            $callbackResult = \call_user_func($this->callback, $this->iterator->current(), $this->count);
            $callbackResult = IteratorHelper::toArrayIterable($callbackResult);
            if (empty($callbackResult)) {
                $this->iterator->next();
            } else {
                $this->buffer = $callbackResult;
            }
        }

        return null !== $this->buffer;
    }

    #[\Override]
    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }
}
