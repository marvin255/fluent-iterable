<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Helper that contains several operations for data accessing and converting.
 *
 * @internal
 */
final class DataAccessor
{
    private function __construct()
    {
    }

    /**
     * Returns data from the set path.
     *
     * @psalm-pure
     *
     * @psalm-suppress MixedAssignment
     */
    public static function get(string $path, array|object $data): mixed
    {
        $arPath = explode('.', trim($path, " \n\r\t\v\0."));

        $item = $data;
        foreach ($arPath as $chainItem) {
            if (\is_array($item) && \array_key_exists($chainItem, $item)) {
                $item = $item[$chainItem];
            } elseif (\is_object($item) && property_exists($item, $chainItem)) {
                $item = $item->$chainItem;
            } else {
                $item = null;
                break;
            }
        }

        return $item;
    }

    /**
     * Returns string data item from the set path.
     *
     * @psalm-pure
     */
    public static function getString(string $path, array|object $data): string
    {
        return (string) self::get($path, $data);
    }
}
