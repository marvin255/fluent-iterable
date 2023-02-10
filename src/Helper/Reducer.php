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
     * Return callable that reduces array of objects to a value of a minimal field.
     *
     * @psalm-return pure-callable(array|object|null, array|object): (array|object)
     */
    public static function minParam(string $paramName): callable
    {
        return function (array|object|null $carry, array|object $item) use ($paramName): array|object {
            $carryValue = $carry === null ? null : DataAccessor::get($paramName, $carry);
            $itemValue = DataAccessor::get($paramName, $item);
            /** @var array|object */
            $res = $carryValue === null || $itemValue < $carryValue ? $item : $carry;

            return $res;
        };
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
     * Return callable that reduces array of objects to a value of a maximal field.
     *
     * @psalm-return pure-callable(array|object|null, array|object): (array|object)
     */
    public static function maxParam(string $paramName): callable
    {
        return function (array|object|null $carry, array|object $item) use ($paramName): array|object {
            $carryValue = $carry === null ? null : DataAccessor::get($paramName, $carry);
            $itemValue = DataAccessor::get($paramName, $item);
            /** @var array|object */
            $res = $carryValue === null || $itemValue > $carryValue ? $item : $carry;

            return $res;
        };
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
     * Return callable that sums all fields in all items.
     *
     * @psalm-return pure-callable(int|float|null, array|object): (int|float)
     */
    public static function sumParam(string $paramName): callable
    {
        return function (int|float|null $carry, array|object $item) use ($paramName): int|float {
            $itemValue = DataAccessor::get($paramName, $item);
            if (!\is_int($itemValue) && !\is_float($itemValue)) {
                throw new \InvalidArgumentException('Param value must be int or float');
            }

            return ($carry === null ? 0 : $carry) + $itemValue;
        };
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
