<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that can convert any iterable entity to the Iterator.
 * All keys will be converted to ints.
 *
 * @template TValue
 *
 * @implements \Iterator<int, TValue>
 *
 * @internal
 */
final class AnySourceIterator implements \Countable, \Iterator
{
    /**
     * @var \Iterator<mixed, TValue>
     */
    private readonly \Iterator $iterator;

    private int $count = 0;

    /**
     * @param iterable<mixed, TValue> $entity
     */
    private function __construct(iterable $entity)
    {
        if (\is_array($entity)) {
            $this->iterator = new ImmutableArrayIterator($entity);
        } elseif ($entity instanceof \Iterator) {
            $this->iterator = $entity;
        } else {
            $this->iterator = new \IteratorIterator($entity);
        }
    }

    /**
     * Create new AnySourceIterator item from the given iterable.
     *
     * @template T
     *
     * @param iterable<mixed, T> $entity
     *
     * @return self<T>
     */
    public static function of(iterable $entity): AnySourceIterator
    {
        return new self($entity);
    }

    /**
     * @return TValue
     */
    #[\Override]
    public function current(): mixed
    {
        return $this->iterator->current();
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
