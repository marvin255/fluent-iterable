<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

use Marvin255\DataGetterHelper\DataGetterHelper;

/**
 * Collection of pre-defined sorters for FluentIterable::sorted.
 *
 * @psalm-api
 */
final class Sorter
{
    private function __construct()
    {
    }

    /**
     * Return sorter for numbers.
     *
     * @psalm-return pure-callable(int|float, int|float): int
     */
    public static function sortNumeric(Order $order = Order::ASC): callable
    {
        return fn (int|float $value1, int|float $value2): int => match ($order) {
            Order::ASC => $value1 <=> $value2,
            Order::DESC => $value2 <=> $value1,
        };
    }

    /**
     * Return sorter for numbers for set object or array param.
     *
     * @psalm-return pure-callable(array|object, array|object): int
     */
    public static function sortNumericParam(string $paramName, Order $order = Order::ASC): callable
    {
        return fn (array|object $value1, array|object $value2): int => match ($order) {
            Order::ASC => DataGetterHelper::get($paramName, $value1) <=> DataGetterHelper::get($paramName, $value2),
            Order::DESC => DataGetterHelper::get($paramName, $value2) <=> DataGetterHelper::get($paramName, $value1),
        };
    }

    /**
     * Return sorter for strings.
     *
     * @psalm-return pure-callable(string, string): int
     */
    public static function sortString(Order $order = Order::ASC): callable
    {
        return fn (string $value1, string $value2): int => match ($order) {
            Order::ASC => strcmp($value1, $value2),
            Order::DESC => strcmp($value2, $value1),
        };
    }

    /**
     * Return sorter for strings.
     *
     * @psalm-return pure-callable(array|object, array|object): int
     */
    public static function sortStringParam(string $paramName, Order $order = Order::ASC): callable
    {
        return fn (array|object $value1, array|object $value2): int => match ($order) {
            Order::ASC => strcmp(DataGetterHelper::string($paramName, $value1), DataGetterHelper::string($paramName, $value2)),
            Order::DESC => strcmp(DataGetterHelper::string($paramName, $value2), DataGetterHelper::string($paramName, $value1)),
        };
    }
}
