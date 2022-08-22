<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Countable;
use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that returns only distinct values from underlying iterator.
 *
 * @template TValue
 * @implements Iterator<int, TValue>
 */
final class DistinctIterator implements Countable, Iterator
{
    /**
     * @var Iterator<mixed, TValue>
     */
    private readonly Iterator $iterator;

    /**
     * @var array<string, TValue>
     */
    private array $cached = [];

    private int $count = 0;

    /**
     * @param Iterator<mixed, TValue> $iterator
     */
    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
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
    }

    public function rewind(): void
    {
        $this->count = 0;
        $this->cached = [];
        $this->iterator->rewind();
    }

    public function valid(): bool
    {
        while ($this->iterator->valid()) {
            $current = $this->iterator->current();
            if ($this->isItemCached($current)) {
                $this->iterator->next();
            } else {
                $this->cacheItem($current);
                break;
            }
        }

        return $this->iterator->valid();
    }

    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }

    /**
     * @param TValue $item
     *
     * @return bool
     */
    private function isItemCached(mixed $item): bool
    {
        $key = $this->createKey($item);

        return isset($this->cached[$key]);
    }

    /**
     * @param TValue $item
     */
    private function cacheItem(mixed $item): void
    {
        $key = $this->createKey($item);
        $this->cached[$key] = $item;
    }

    /**
     * @param TValue $item
     *
     * @return string
     */
    private function createKey(mixed $item): string
    {
        return \is_object($item) ? spl_object_hash($item) : (string) $item;
    }
}
