<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;

/**
 * Immutable iterator for arrays.
 *
 * @implements \Iterator<int, mixed>
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

    #[\Override]
    public function current(): mixed
    {
        return $this->array[$this->counter];
    }

    #[\Override]
    public function key(): int
    {
        return $this->counter;
    }

    #[\Override]
    public function next(): void
    {
        ++$this->counter;
    }

    #[\Override]
    public function rewind(): void
    {
        $this->counter = 0;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->counter < $this->arrayCount;
    }

    #[\Override]
    public function count(): int
    {
        return $this->arrayCount;
    }
}
