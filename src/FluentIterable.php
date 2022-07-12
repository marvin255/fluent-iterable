<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable;

use CallbackFilterIterator;
use Iterator;
use Marvin255\FluentIterable\Iterator\AnySourceIterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\FluentIterable\Iterator\MergedIteratorsIterator;
use Marvin255\FluentIterable\Iterator\SliceIterator;
use Marvin255\Optional\Optional;

/**
 * Fluent interface for any iterable entity.
 *
 * @template TValue
 */
final class FluentIterable
{
    /**
     * @var Iterator<int, TValue>
     */
    private readonly Iterator $iterator;

    /**
     * @param Iterator<int, TValue> $iterator
     */
    private function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * Create new FluentIterable item from the given iterable.
     *
     * @template T
     *
     * @param iterable<mixed, T> $iterable
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
    public static function of(iterable $iterable): self
    {
        return new self(AnySourceIterator::of($iterable));
    }

    /**
     * Filter elements of an iterable using a callback function.
     *
     * @param iterable<mixed, TValue> $iterable
     *
     * @return self<TValue>
     */
    public function merge(iterable $iterable): self
    {
        $newIterator = AnySourceIterator::of($iterable);
        $mergedIterator = MergedIteratorsIterator::of($this->iterator, $newIterator);

        return new self($mergedIterator);
    }

    /**
     * Filter elements of an iterable using a callback function.
     *
     * @param callable $callback
     *
     * @return self<TValue>
     *
     * @psalm-param callable(TValue, int=): bool $callback
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
     *
     * @param callable $callback
     *
     * @return self<TConverted>
     *
     * @psalm-param callable(TValue, int=): TConverted $callback
     */
    public function map(callable $callback): self
    {
        $iterator = new CallbackMapIterator($this->iterator, $callback);

        return new self($iterator);
    }

    /**
     * Apply a user supplied function to every member of an iterable.
     *
     * @param int $offset
     *
     * @return self<TValue>
     */
    public function skip(int $offset): self
    {
        $iterator = new SliceIterator($this->iterator, $offset);

        return new self($iterator);
    }

    /**
     * Apply a user supplied function to every member of an iterable.
     *
     * @param callable $callback
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
     *
     * @param callable   $callback
     * @param TConverted $initial
     *
     * @return Optional<TConverted>
     *
     * @psalm-param callable(TConverted, TValue, int=): TConverted $callback
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
     * @return Optional<TValue>
     */
    public function findFirst(): Optional
    {
        return $this->iterator->valid()
            ? Optional::of($this->iterator->current())
            : Optional::empty();
    }

    /**
     * Return the last item from an iterable.
     *
     * @return Optional<TValue>
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
     * @return array<int, TValue>
     */
    public function toArray(): array
    {
        return iterator_to_array($this->iterator, false);
    }

    /**
     * Return the internal iterator.
     *
     * @return Iterator<int, TValue>
     */
    public function toIterator(): Iterator
    {
        return $this->iterator;
    }
}
