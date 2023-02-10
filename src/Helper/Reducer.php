<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Collection of pre-defined reducers for FluentIterable::reduce.
 */
final class Reducer
{
    /**
     * @var \Closure[]
     */
    private static array $cachedReducers = [];

    private function __construct()
    {
    }

    /**
     * Return callable that reduces numeric array to a minimal item.
     *
     * @psalm-return pure-callable(int|float|null, int|float): (int|float)
     */
    public static function min(): callable
    {
        if (!isset(self::$cachedReducers['min'])) {
            self::$cachedReducers['min'] = function (int|float|null $carry, int|float $item): int|float {
                return $carry === null || $item < $carry ? $item : $carry;
            };
        }

        /** @psalm-var pure-callable(int|float|null, int|float): (int|float) */
        $res = self::$cachedReducers['min'];

        return $res;
    }

    /**
     * Return callable that reduces numeric array to a maximal item.
     *
     * @psalm-return pure-callable(int|float|null, int|float): (int|float)
     */
    public static function max(): callable
    {
        if (!isset(self::$cachedReducers['max'])) {
            self::$cachedReducers['max'] = function (int|float|null $carry, int|float $item): int|float {
                return $carry === null || $item > $carry ? $item : $carry;
            };
        }

        /** @psalm-var pure-callable(int|float|null, int|float): (int|float) */
        $res = self::$cachedReducers['max'];

        return $res;
    }

    /**
     * Return callable that sums all items.
     *
     * @psalm-return pure-callable(int|float|null, int|float|null): (int|float)
     */
    public static function sum(): callable
    {
        if (!isset(self::$cachedReducers['sum'])) {
            self::$cachedReducers['sum'] = function (int|float|null $carry, int|float|null $item): int|float {
                return ($carry === null ? 0 : $carry) + ($item === null ? 0 : $item);
            };
        }

        /** @psalm-var pure-callable(int|float|null, int|float|null): (int|float) */
        $res = self::$cachedReducers['sum'];

        return $res;
    }

    /**
     * Return callable that implodes all items to a string.
     *
     * @psalm-return pure-callable(int|float|string|null, int|float|string|null): string
     */
    public static function join(string $separator = ''): callable
    {
        return function (int|float|string|null $carry, int|float|string|null $item) use ($separator): string {
            return ($carry === null ? '' : $carry . $separator) . ($item ?? '');
        };
    }
}
