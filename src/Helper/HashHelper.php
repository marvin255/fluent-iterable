<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Helper for hashing.
 */
final class HashHelper
{
    private function __construct()
    {
    }

    /**
     * @param mixed $item
     *
     * @return string
     */
    public static function createHashForData(mixed $item): string
    {
        if (\is_string($item)) {
            return 'str' . (\strlen($item) > 35 ? md5($item) : $item);
        } elseif (\is_float($item) || \is_bool($item) || \is_int($item)) {
            return \gettype($item) . $item;
        } elseif (\is_object($item)) {
            return 'obj' . md5(serialize($item));
        } else {
            return 'cust' . md5(json_encode($item));
        }
    }
}
