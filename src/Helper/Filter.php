<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Collection of pre-defined filters for FluentIterable::filter.
 */
final class Filter
{
    private function __construct()
    {
    }

    /**
     * Return filter that filters only values that equal to set parameter.
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
}
