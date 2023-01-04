<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;

/**
 * Iterator that converts every item using callback function.
 * All keys will be converted to ints.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 */
final class SliceIterator implements \Iterator
{
    /**
     * @var \Iterator<mixed, TValue>
     */
    private readonly \Iterator $iterator;

    private readonly ?int $offset;

    private readonly ?int $length;

    private int $count = 0;

    /**
     * @param \Iterator<mixed, TValue> $iterator
     * @param ?int                     $offset
     * @param ?int                     $length
     */
    public function __construct(\Iterator $iterator, ?int $offset = null, ?int $length = null)
    {
        if ($offset !== null && $offset < 0) {
            throw new \InvalidArgumentException("\"from\" parameter can't be less than 0");
        } elseif ($length !== null && $length <= 0) {
            throw new \InvalidArgumentException("\"length\" parameter can't be less than 0");
        }

        $this->iterator = $iterator;
        $this->offset = $offset;
        $this->length = $length;
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
        return $this->offset !== null
            ? $this->count - $this->offset
            : $this->count;
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
        $offset = $this->offset === null ? 0 : $this->offset;
        if ($this->length !== null && $this->count > $offset + $this->length - 1) {
            return false;
        }

        while ($this->offset !== null && $this->count < $this->offset) {
            $this->next();
        }

        return $this->iterator->valid();
    }
}
