<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\Helper\HashHelper;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that returns only distinct values from underlying iterator.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 *
 * @internal
 */
final class DistinctIterator implements \Countable, \Iterator
{
    /**
     * @var \Iterator<mixed, TValue>
     */
    private readonly \Iterator $iterator;

    /**
     * @var array<string, bool>
     */
    private array $cached = [];

    private int $count = 0;

    /**
     * @param \Iterator<mixed, TValue> $iterator
     */
    public function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
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
    }

    #[\Override]
    public function rewind(): void
    {
        $this->count = 0;
        $this->cached = [];
        $this->iterator->rewind();
    }

    #[\Override]
    public function valid(): bool
    {
        while ($this->iterator->valid()) {
            $item = $this->iterator->current();
            $key = HashHelper::createHashForData($item);
            if ($this->isItemCached($key)) {
                $this->iterator->next();
            } else {
                $this->cacheItem($key);
                break;
            }
        }

        return $this->iterator->valid();
    }

    #[\Override]
    public function count(): int
    {
        return IteratorHelper::count($this->iterator);
    }

    private function isItemCached(string $key): bool
    {
        return !empty($this->cached[$key]);
    }

    private function cacheItem(string $key): void
    {
        $this->cached[$key] = true;
    }
}
