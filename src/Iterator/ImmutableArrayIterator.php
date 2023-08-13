<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;

/**
 * Immutable iterator for arrays.
 *
 * @implements Iterator<int, mixed>
 *
 * @internal
 */
final class ImmutableArrayIterator implements \Countable, \Iterator
{
    /**
     * @var array<int, mixed>
     */
    private readonly array $array;

    private readonly int $arrayCount;

    private int $counter = 0;

    public function __construct(array $array)
    {
        $this->array = array_values($array);
        $this->arrayCount = \count($array);
    }

    public function current(): mixed
    {
        return $this->array[$this->counter];
    }

    public function key(): int
    {
        return $this->counter;
    }

    public function next(): void
    {
        ++$this->counter;
    }

    public function rewind(): void
    {
        $this->counter = 0;
    }

    public function valid(): bool
    {
        return $this->counter < $this->arrayCount;
    }

    public function count(): int
    {
        return $this->arrayCount;
    }
}
