<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Iterator;

use Countable;
use InvalidArgumentException;
use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;

/**
 * Iterator that can iterates over multiple nested iterators.
 *
 * Main differences with AppendIterator are:
 * - creates new numeric keys for each element
 * - doesn't run any transformations and conversions
 *
 * @implements Iterator<int, mixed>
 */
final class MergedIteratorsIterator implements Countable, Iterator
{
    /**
     * @var Iterator[]
     */
    private readonly array $iterators;

    private int $itemCounter = 0;

    private int $iteratorCounter = 0;

    public function __construct(iterable $iterators)
    {
        $checkedIterators = [];
        foreach ($iterators as $iterator) {
            if (!($iterator instanceof Iterator)) {
                throw new InvalidArgumentException('All items must be instance of Iterator or array');
            }
            $checkedIterators[] = $iterator;
        }
        $this->iterators = $checkedIterators;
    }

    public static function of(iterable ...$iterators): MergedIteratorsIterator
    {
        return new self($iterators);
    }

    public function current(): mixed
    {
        return $this->getCurrentIterator()->current();
    }

    public function key(): int
    {
        return $this->itemCounter;
    }

    public function next(): void
    {
        ++$this->itemCounter;
        $this->getCurrentIterator()->next();
    }

    public function rewind(): void
    {
        $this->itemCounter = 0;
        $this->iteratorCounter = 0;
        foreach ($this->iterators as $iterator) {
            $iterator->rewind();
        }
    }

    public function valid(): bool
    {
        if (!isset($this->iterators[$this->iteratorCounter])) {
            return false;
        }

        $isValid = $this->iterators[$this->iteratorCounter]->valid();
        while (!$isValid && isset($this->iterators[++$this->iteratorCounter])) {
            $isValid = $this->iterators[$this->iteratorCounter]->valid();
        }

        return $isValid;
    }

    public function count(): int
    {
        $count = 0;
        foreach ($this->iterators as $iterator) {
            $count += IteratorHelper::count($iterator);
        }

        return $count;
    }

    private function getCurrentIterator(): Iterator
    {
        return $this->iterators[$this->iteratorCounter];
    }
}
