<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Countable;
use Iterator;
use IteratorIterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that can convert any iterable entity to the Iterator.
 * All keys will be converted to ints.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 */
final class AnySourceIterator implements Countable, Iterator
{
    /**
     * @var Iterator<mixed, TValue>
     */
    private readonly Iterator $iterator;

    private int $count = 0;

    /**
     * @param iterable<mixed, TValue> $entity
     */
    private function __construct(iterable $entity)
    {
        if (\is_array($entity)) {
            $this->iterator = new ImmutableArrayIterator($entity);
        } elseif ($entity instanceof Iterator) {
            $this->iterator = $entity;
        } else {
            $this->iterator = new IteratorIterator($entity);
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
     *
     * @psalm-return (
     *     T is string ? self<string> : (
     *         T is int ? self<int> : (
     *             T is bool ? self<bool> : (
     *                 T is float ? self<float> : self<T>
     *             )
     *         )
     *     )
     * )
     */
    public static function of(iterable $entity): AnySourceIterator
    {
        return new self($entity);
    }

    /**
     * @return TValue
     */
    public function current(): mixed
    {
        return $this->iterator->current();
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
