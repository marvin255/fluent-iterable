<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use Countable;
use Iterator;

/**
 * Test case for iterators.
 */
abstract class IteratorCase extends BaseCase
{
    /**
     * Asserts that iterator contains same items as set array.
     */
    protected function assertIteratorContains(array $content, Iterator $iterator): void
    {
        $result = $this->runLoopOnIterator($iterator, 1);

        $this->assertSame($content, $result);
    }

    /**
     * Asserts that Countable count returns correct count value.
     */
    protected function assertCountableCount(int $count, Countable $countable): void
    {
        $result = \count($countable);

        $this->assertSame($count, $result, "Countable instance must return {$count} for count");
    }

    /**
     * Runs foreach loop on this iterator, collects all items to array and returns results.
     */
    protected function runLoopOnIterator(Iterator $iterator, int $iterationCount = 2): array
    {
        $result = [];

        for ($i = 0; $i < $iterationCount; ++$i) {
            $result = [];
            foreach ($iterator as $key => $item) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
