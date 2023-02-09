<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Collection of usefull functions to work with iterators.
 *
 * @internal
 */
final class IteratorHelper
{
    private function __construct()
    {
    }

    public static function count(\Iterator $iterator): int
    {
        return $iterator instanceof \Countable
            ? $iterator->count()
            : iterator_count($iterator);
    }

    /**
     * @template T
     *
     * @param \Iterator<mixed, T> $iterator
     *
     * @return array<int, T>
     */
    public static function toArray(\Iterator $iterator): array
    {
        return iterator_to_array($iterator, false);
    }

    /**
     * @template T
     *
     * @param iterable<mixed, T> $iterable
     *
     * @return array<int, T>
     */
    public static function toArrayIterable(iterable $iterable): array
    {
        if (\is_array($iterable)) {
            return array_values($iterable);
        }

        $result = [];
        foreach ($iterable as $item) {
            $result[] = $item;
        }

        return $result;
    }
}
