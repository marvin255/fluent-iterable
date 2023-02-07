<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Collection of pre-defined filters for FluentIterable::filter.
 */
final class Filter
{
    /**
     * @var \Closure[]
     */
    private static array $cachedFilters = [];

    private function __construct()
    {
    }

    /**
     * Return filter that compares value with set operator and operand.
     *
     * @psalm-return pure-callable(mixed): bool
     */
    public static function compare(Compare $operator, mixed $operand): callable
    {
        return fn (mixed $value): bool => match ($operator) {
            Compare::EQUAL => $value === $operand,
            Compare::NOT_EQUAL => $value !== $operand,
            Compare::GREATER_THAN => $value > $operand,
            Compare::GREATER_THAN_OR_EQUAL => $value >= $operand,
            Compare::LESS_THEN => $value < $operand,
            Compare::LESS_THEN_OR_EQUAL => $value <= $operand,
        };
    }

    /**
     * Return filter that filters not null values.
     *
     * @psalm-return pure-callable(mixed): bool
     */
    public static function notNull(): callable
    {
        if (!isset(self::$cachedFilters['notNull'])) {
            self::$cachedFilters['notNull'] = fn (mixed $value): bool => $value !== null;
        }

        /** @psalm-var pure-callable(mixed): bool */
        $res = self::$cachedFilters['notNull'];

        return $res;
    }

    /**
     * Return filter that filters not empty values.
     *
     * @psalm-return pure-callable(mixed): bool
     */
    public static function notEmpty(): callable
    {
        if (!isset(self::$cachedFilters['notEmpty'])) {
            self::$cachedFilters['notEmpty'] = fn (mixed $value): bool => !empty($value);
        }

        /** @psalm-var pure-callable(mixed): bool */
        $res = self::$cachedFilters['notEmpty'];

        return $res;
    }

    /**
     * Return filter that filters value using set regexp.
     *
     * @psalm-return pure-callable(string): bool
     */
    public static function regexp(string $regexp): callable
    {
        return fn (string $value): bool => preg_match($regexp, $value) === 1;
    }
}
