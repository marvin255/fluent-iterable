<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use InvalidArgumentException;
use Iterator;

/**
 * Iterator that converts every item using callback function.
 * All keys will be converted to ints.
 *
 * @template TValue
 * @implements Iterator<int, TValue>
 */
class SliceIterator implements Iterator
{
    /**
     * @var Iterator<mixed, TValue>
     */
    private readonly Iterator $iterator;

    private readonly ?int $from;

    private readonly ?int $to;

    private int $count = 0;

    /**
     * @param Iterator<mixed, TValue> $iterator
     * @param ?int                    $from
     * @param ?int                    $to
     */
    public function __construct(Iterator $iterator, ?int $from = null, ?int $to = null)
    {
        if ($from !== null && $from < 0) {
            throw new InvalidArgumentException("\"From\" parameter can't be less than 0");
        } elseif ($to !== null && $to < 0) {
            throw new InvalidArgumentException("\"To\" parameter can't be less than 0");
        } elseif ($from !== null && $to !== null && $from > $to) {
            throw new InvalidArgumentException("\"From\" parameter can't be more than \"To\" parameter");
        }

        $this->iterator = $iterator;
        $this->from = $from;
        $this->to = $to;
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
        return $this->from !== null
            ? $this->count - $this->from
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
        if ($this->to !== null && $this->count > $this->to) {
            return false;
        }

        while ($this->from !== null && $this->count < $this->from) {
            $this->next();
        }

        return $this->iterator->valid();
    }
}
