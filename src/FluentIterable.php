<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable;

use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;
use Marvin255\FluentIterable\Iterator\AnySourceIterator;
use Marvin255\FluentIterable\Iterator\CallbackFilterIterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\FluentIterable\Iterator\DistinctIterator;
use Marvin255\FluentIterable\Iterator\FlattenIterator;
use Marvin255\FluentIterable\Iterator\MergedIteratorsIterator;
use Marvin255\FluentIterable\Iterator\PeekIterator;
use Marvin255\FluentIterable\Iterator\SliceIterator;
use Marvin255\FluentIterable\Iterator\SortedIterator;
use Marvin255\Optional\Optional;

/**
 * Fluent interface for any iterable entity.
 *
 * @template TValue
 *
 * @template-implements \IteratorAggregate<int, TValue>
 */
final class FluentIterable implements \IteratorAggregate
{
    /**
     * @var \Iterator<int, TValue>
     */
    private readonly \Iterator $iterator;

    /**
     * @param \Iterator<int, TValue> $iterator
     */
    private function __construct(\Iterator $iterator)
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
        $iterator = new CallbackFilterIterator($this->iterator, $filter);

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
     * Set maximal number of items in response.
     *
     * @param int $limit
     *
     * @return self<TValue>
     */
    public function limit(int $limit): self
    {
        $iterator = new SliceIterator($this->iterator, 0, $limit);

        return new self($iterator);
    }

    /**
     * Set sorting for current iterating data.
     *
     * @param callable $callback
     *
     * @return self<TValue>
     *
     * @psalm-param callable(TValue, TValue): int $callback
     */
    public function sorted(callable $callback): self
    {
        $iterator = new SortedIterator($this->iterator, $callback);

        return new self($iterator);
    }

    /**
     * Return only distinct elements.
     *
     * @return self<TValue>
     */
    public function distinct(): self
    {
        $iterator = new DistinctIterator($this->iterator);

        return new self($iterator);
    }

    /**
     * Return an iterator which contains flat list of items returned by callable applied to every item of main iterator.
     *
     * @template TConverted
     *
     * @param callable $callback
     *
     * @return self<TConverted>
     *
     * @psalm-param callable(TValue, int=): iterable<TConverted> $callback
     */
    public function flatten(callable $callback): self
    {
        $iterator = new FlattenIterator($this->iterator, $callback);

        return new self($iterator);
    }

    /**
     * Returns an iterator consisting of the elements of this iterator,
     * additionally performing the provided action on each element as elements are consumed from the resulting iterator.
     *
     * @param callable $callback
     *
     * @return self<TValue>
     *
     * @psalm-param callable(TValue, int=): void $callback
     */
    public function peek(callable $callback): self
    {
        $iterator = new PeekIterator($this->iterator, $callback);

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
     * @param callable        $callback
     * @param TConverted|null $initial
     *
     * @return Optional<TConverted>
     *
     * @psalm-param callable(TConverted|null, TValue, int=): TConverted $callback
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
     * Return item by set index.
     *
     * @return Optional<TValue>
     */
    public function findByIndex(int $index): Optional
    {
        $itemByIndex = null;
        foreach ($this->iterator as $key => $item) {
            if ($key === $index) {
                $itemByIndex = $item;
                break;
            }
        }

        return Optional::ofNullable($itemByIndex);
    }

    /**
     * Return the first item from an iterable.
     *
     * @return Optional<TValue>
     */
    public function findFirst(): Optional
    {
        return $this->findByIndex(0);
    }

    /**
     * Return the last item from an iterable.
     *
     * @return Optional<TValue>
     */
    public function findLast(): Optional
    {
        // this approach is much faster then findByIndex + count
        $lastItem = null;
        foreach ($this->iterator as $item) {
            $lastItem = $item;
        }

        return Optional::ofNullable($lastItem);
    }

    /**
     * Return true if all elements of iterator match callback.
     *
     * @param callable $callback
     *
     * @return bool
     *
     * @psalm-param callable(TValue, int=): bool $callback
     */
    public function matchAll(callable $callback): bool
    {
        $res = true;
        foreach ($this->iterator as $key => $item) {
            if (!\call_user_func($callback, $item, $key)) {
                $res = false;
                break;
            }
        }

        return $res;
    }

    /**
     * Return true if at least one element of iterator matches callback.
     *
     * @param callable $callback
     *
     * @return bool
     *
     * @psalm-param callable(TValue, int=): bool $callback
     */
    public function matchAny(callable $callback): bool
    {
        $res = false;
        foreach ($this->iterator as $key => $item) {
            if (\call_user_func($callback, $item, $key)) {
                $res = true;
                break;
            }
        }

        return $res;
    }

    /**
     * Return true if no element of iterator matches callback.
     *
     * @param callable $callback
     *
     * @return bool
     *
     * @psalm-param callable(TValue, int=): bool $callback
     */
    public function matchNone(callable $callback): bool
    {
        return !$this->matchAny($callback);
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
     * @return \Iterator<int, TValue>
     */
    public function getIterator(): \Iterator
    {
        return $this->iterator;
    }
}
