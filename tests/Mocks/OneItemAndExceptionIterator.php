<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Mocks;

use Iterator;
use RuntimeException;

/**
 * Iterator that allows only one iteration and tthrow exception on the second iteration.
 */
class OneItemAndExceptionIterator implements Iterator
{
    private int $counter = 0;

    public function current(): mixed
    {
        return $this->counter;
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
        if ($this->counter > 1) {
            throw new RuntimeException("Can't iterate over 3");
        }

        return true;
    }
}
