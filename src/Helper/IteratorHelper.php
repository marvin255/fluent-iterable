<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

use Countable;
use Iterator;

/**
 * Collection of usefull functions to work with iterators.
 */
final class IteratorHelper
{
    public static function count(Iterator $iterator): int
    {
        return $iterator instanceof Countable
            ? $iterator->count()
            : iterator_count($iterator);
    }

    public static function toArray(Iterator $iterator): array
    {
        return iterator_to_array($iterator, false);
    }
}
