<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests;

use Countable;
use Exception;
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
        $result = $this->runLoopOnIterator($iterator);

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
     *
     * @psalm-suppress MixedArrayOffset
     */
    protected function runLoopOnIterator(Iterator $iterator): array
    {
        $result = [];
        foreach ($iterator as $key => $item) {
            $result[$key] = $item;
        }

        try {
            $secondRun = [];
            foreach ($iterator as $key => $item) {
                $secondRun[$key] = $item;
            }
            $result = $secondRun;
        } catch (Exception $e) {
            if ($e->getMessage() !== 'Cannot rewind a generator that was already run') {
                throw $e;
            }
        }

        return $result;
    }
}
