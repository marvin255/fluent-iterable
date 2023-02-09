<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that converts internal iteartor to array and sorts it with set sort callback.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 */
final class SortedIterator implements \Iterator
{
    /**
     * @var \Iterator<mixed, TValue>
     */
    private readonly \Iterator $iterator;

    /**
     * @var callable
     *
     * @psalm-var callable(TValue, TValue): int
     */
    private readonly mixed $callback;

    /**
     * @var TValue[]
     */
    private array $array = [];

    private bool $isInited = false;

    private int $count = 0;

    /**
     * @param \Iterator<mixed, TValue> $iterator
     *
     * @psalm-param callable(TValue, TValue): int $callback
     */
    public function __construct(\Iterator $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * @return TValue
     */
    public function current(): mixed
    {
        $this->initArray();

        return $this->array[$this->count];
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
    }

    public function valid(): bool
    {
        $this->initArray();

        return $this->count < \count($this->array);
    }

    private function initArray(): void
    {
        if ($this->isInited) {
            return;
        }

        $this->isInited = true;
        $this->array = IteratorHelper::toArray($this->iterator);

        usort($this->array, $this->callback);
    }
}
