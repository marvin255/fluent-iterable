<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use ArrayObject;
use Iterator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * Base test case for all tests.
 */
abstract class BaseCase extends TestCase
{
    protected function createIteratorAggregate(mixed ...$data): IteratorAggregate
    {
        return new ArrayObject($data);
    }

    protected function createIterator(mixed ...$data): Iterator
    {
        return (new ArrayObject($data))->getIterator();
    }

    protected function createIteratorFromArray(array $data): Iterator
    {
        return (new ArrayObject($data))->getIterator();
    }

    protected function createEmptyIterator(): Iterator
    {
        return (new ArrayObject([]))->getIterator();
    }
}
