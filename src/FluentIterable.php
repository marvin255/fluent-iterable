<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable;

use CallbackFilterIterator;
use Iterator;
use Marvin255\FluentIterable\Iterator\AnySourceIterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\Optional\Optional;

/**
 * Fluent interface for any iterable entity.
 *
 * @psalm-template TValue
 */
final class FluentIterable
{
    /**
     * @psalm-var Iterator<int, TValue>
     */
    private readonly Iterator $iterator;

    /**
     * @psalm-param Iterator<int, TValue> $iterator
     */
    private function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * Create new FluentIterable item from the given iterable.
     *
     * @template T
     * @psalm-param iterable<mixed, T> $iterable
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
    public static function of(iterable $iterable): self
    {
        return new self(AnySourceIterator::of($iterable));
    }

    /**
     * Filter elements of an iterable using a callback function.
     *
     * @psalm-param callable(TValue, int=): bool $callback
     * @psalm-return self<TValue>
     */
    public function filter(callable $callback): self
    {
        $filterCallback = function (mixed $current, int $key, Iterator $iterator) use ($callback): bool {
            /** @psalm-var TValue */
            $item = $current;

            return \call_user_func($callback, $item, $key);
        };

        $iterator = new CallbackFilterIterator($this->iterator, $filterCallback);

        return new self($iterator);
    }

    /**
     * Apply the callback to the elements of the given iterable and collect converted values.
     *
     * @template TConverted
     * @psalm-param callable(TValue, int=): TConverted $callback
     * @psalm-return self<TConverted>
     */
    public function map(callable $callback): self
    {
        $iterator = new CallbackMapIterator($this->iterator, $callback);

        return new self($iterator);
    }

    /**
     * Apply a user supplied function to every member of an iterable.
     *
     * @psalm-param callable(TValue, int=): void $callback
     */
    public function walk(callable $callback): void
    {
        foreach ($this->iterator as $key => $item) {
            \call_user_func($callback, $item, $key);
        }
    }

    /**
     * Iteratively reduce the iterable to a single value using a callback function.
     *
     * @template TConverted
     * @psalm-param callable(TConverted, TValue, int=): TConverted $callback
     * @psalm-param TConverted $initial
     * @psalm-return Optional<TConverted>
     */
    public function reduce(callable $callback, mixed $initial = null): Optional
    {
        foreach ($this->iterator as $key => $item) {
            $initial = \call_user_func($callback, $initial, $item, $key);
        }

        return Optional::ofNullable($initial);
    }

    /**
     * Return the first item from an iterable.
     *
     * @psalm-return Optional<TValue>
     */
    public function findFirst(): Optional
    {
        $this->iterator->next();

        return $this->iterator->valid()
            ? Optional::of($this->iterator->current())
            : Optional::empty();
    }

    /**
     * Return the last item from an iterable.
     *
     * @psalm-return Optional<TValue>
     */
    public function findLast(): Optional
    {
        $lastItem = null;
        foreach ($this->iterator as $item) {
            $lastItem = $item;
        }

        return Optional::ofNullable($lastItem);
    }

    /**
     * Convert the internal data to an array and return it.
     *
     * @psalm-return array<int, TValue>
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->iterator as $item) {
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Return the internal iterator.
     *
     * @psalm-return Iterator<int, TValue>
     */
    public function toIterator(): Iterator
    {
        return $this->iterator;
    }
}
