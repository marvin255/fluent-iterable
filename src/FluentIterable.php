<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable;

use CallbackFilterIterator;
use Iterator;
use IteratorAggregate;
use Marvin255\FluentIterable\Helper\IteratorHelper;
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
final class FluentIterable implements IteratorAggregate
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
     * Filter elements of an iterable using a filter function.
     *
     * @param callable $filter
     *
     * @return self<TValue>
     *
     * @psalm-param callable(TValue, int=): bool $filter
     */
    public function filter(callable $filter): self
    {
        $filterCallback = function (mixed $current, int $key, Iterator $iterator) use ($filter): bool {
            /** @psalm-var TValue */
            $item = $current;

            return \call_user_func($filter, $item, $key);
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
     * Skip some element in the beginning of list.
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
     * Return just a slice from offset with set length.
     *
     * @param ?int $offset
     * @param ?int $length
     *
     * @return self<TValue>
     */
    public function slice(?int $offset = null, ?int $length = null): self
    {
        $iterator = new SliceIterator($this->iterator, $offset, $length);

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
     * Return minimal item using set comparator.
     *
     * @param callable $comparator
     *
     * @return Optional<TValue>
     *
     * @psalm-param callable(TValue, TValue): int $comparator
     */
    public function min(callable $comparator): Optional
    {
        $min = null;
        foreach ($this->iterator as $item) {
            if ($min === null || \call_user_func($comparator, $item, $min) < 0) {
                $min = $item;
            }
        }

        return Optional::ofNullable($min);
    }

    /**
     * Return maximal item using set comparator.
     *
     * @param callable $comparator
     *
     * @return Optional<TValue>
     *
     * @psalm-param callable(TValue, TValue): int $comparator
     */
    public function max(callable $comparator): Optional
    {
        $max = null;
        foreach ($this->iterator as $item) {
            if ($max === null || \call_user_func($comparator, $item, $max) > 0) {
                $max = $item;
            }
        }

        return Optional::ofNullable($max);
    }

    /**
     * Return the first item that fits the filter and breaks the loop.
     *
     * @param callable $filter
     *
     * @return Optional<TValue>
     *
     * @psalm-param callable(TValue, int=): bool $filter
     */
    public function findOne(callable $filter): Optional
    {
        $found = null;
        foreach ($this->iterator as $key => $item) {
            if (\call_user_func($filter, $item, $key)) {
                $found = $item;
                break;
            }
        }

        return Optional::ofNullable($found);
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
     * Returns number of items in those iterable.
     *
     * @return int
     */
    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }

    /**
     * Convert the internal data to an array and return it.
     *
     * @return array<int, TValue>
     */
    public function toArray(): array
    {
        return IteratorHelper::toArray($this->iterator);
    }

    /**
     * Return the internal iterator.
     *
     * @return Iterator<int, TValue>
     */
    public function getIterator(): Iterator
    {
        return $this->iterator;
    }
}
