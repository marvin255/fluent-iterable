<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Iterator;
use Marvin255\FluentIterable\FluentIterableException;

/**
 * Iterator that converts every item using callback function.
 * All keys will be converted to ints.
 *
 * @template TValue
 *
 * @implements Iterator<int, TValue>
 *
 * @internal
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
    public function __construct(\Iterator $iterator, int $offset = null, int $length = null)
    {
        if (null !== $offset && $offset < 0) {
            throw new FluentIterableException("\"from\" parameter can't be less than 0");
        } elseif (null !== $length && $length <= 0) {
            throw new FluentIterableException("\"length\" parameter can't be less than 0");
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
        return null !== $this->offset
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
        $offset = null === $this->offset ? 0 : $this->offset;
        if (null !== $this->length && $this->count > $offset + $this->length - 1) {
            return false;
        }

        while (null !== $this->offset && $this->count < $this->offset) {
            $this->next();
        }

        return $this->iterator->valid();
    }
}
