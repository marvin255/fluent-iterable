<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Helper;

/**
 * Collection of pre-defined mappers for FluentIterable::map.
 *
 * @psalm-api
 */
final class Mapper
{
    /**
     * @var \Closure[]
     */
    private static array $cachedMappers = [];

    private function __construct()
    {
    }

    /**
     * Return mapper that casts mixed values to integers.
     *
     * @psalm-return pure-callable(mixed): int
     */
    public static function int(): callable
    {
        if (!isset(self::$cachedMappers['int'])) {
            self::$cachedMappers['int'] = fn (mixed $value): int => (int) $value;
        }

        /** @psalm-var pure-callable(mixed): int */
        $res = self::$cachedMappers['int'];

        return $res;
    }

    /**
     * Return mapper that casts mixed values to floats.
     *
     * @psalm-return pure-callable(mixed): float
     */
    public static function float(): callable
    {
        if (!isset(self::$cachedMappers['float'])) {
            self::$cachedMappers['float'] = fn (mixed $value): float => (float) $value;
        }

        /** @psalm-var pure-callable(mixed): float */
        $res = self::$cachedMappers['float'];

        return $res;
    }

    /**
     * Return mapper that casts mixed values to booleans.
     *
     * @psalm-return pure-callable(mixed): bool
     */
    public static function bool(): callable
    {
        if (!isset(self::$cachedMappers['bool'])) {
            self::$cachedMappers['bool'] = fn (mixed $value): bool => (bool) $value;
        }

        /** @psalm-var pure-callable(mixed): bool */
        $res = self::$cachedMappers['bool'];

        return $res;
    }

    /**
     * Return mapper that converts mixed values to DateTimeImmutable.
     *
     * @psalm-return callable(string): \DateTimeImmutable
     */
    public static function date(\DateTimeZone $timeZone = null): callable
    {
        return fn (string $value): \DateTimeImmutable => new \DateTimeImmutable($value, $timeZone);
    }

    /**
     * Return mapper that converts mixed values to DateTime.
     *
     * @psalm-return callable(string): \DateTime
     */
    public static function dateMutable(\DateTimeZone $timeZone = null): callable
    {
        return fn (string $value): \DateTime => new \DateTime($value, $timeZone);
    }

    /**
     * Return mapper that converts dates to strings with set formats.
     *
     * @psalm-return callable(\DateTimeInterface): string
     */
    public static function dateFormat(string $format): callable
    {
        return fn (\DateTimeInterface $value): string => $value->format($format);
    }

    /**
     * Return mapper that casts mixed values to strings.
     *
     * @psalm-return pure-callable(mixed): string
     */
    public static function string(): callable
    {
        if (!isset(self::$cachedMappers['string'])) {
            self::$cachedMappers['string'] = fn (mixed $value): string => (string) $value;
        }

        /** @psalm-var pure-callable(mixed): string */
        $res = self::$cachedMappers['string'];

        return $res;
    }

    /**
     * Return mapper that converts all inputs to upper case strings.
     *
     * @psalm-return pure-callable(mixed): string
     */
    public static function upperCase(): callable
    {
        if (!isset(self::$cachedMappers['upperCase'])) {
            self::$cachedMappers['upperCase'] = fn (mixed $value): string => strtoupper((string) $value);
        }

        /** @psalm-var pure-callable(mixed): string */
        $res = self::$cachedMappers['upperCase'];

        return $res;
    }

    /**
     * Return mapper that converts all inputs to lower case strings.
     *
     * @psalm-return pure-callable(mixed): string
     */
    public static function lowerCase(): callable
    {
        if (!isset(self::$cachedMappers['lowerCase'])) {
            self::$cachedMappers['lowerCase'] = fn (mixed $value): string => strtolower((string) $value);
        }

        /** @psalm-var pure-callable(mixed): string */
        $res = self::$cachedMappers['lowerCase'];

        return $res;
    }

    /**
     * Return mapper that trims all input strings.
     *
     * @psalm-return pure-callable(mixed): string
     */
    public static function trim(string $characters = " \n\r\t\v\x00"): callable
    {
        return fn (mixed $value): string => trim((string) $value, $characters);
    }

    /**
     * Return mapper that extracts named param from an object or an array.
     *
     * @psalm-return pure-callable(array|object): mixed
     */
    public static function pluck(string $key, mixed $default = null): callable
    {
        return fn (array|object $value): mixed => DataAccessor::get($key, $value) ?? $default;
    }

    /**
     * Return mapper that constructs object of the set class using item as param.
     *
     * @template CN
     *
     * @psalm-param class-string<CN> $className
     *
     * @psalm-return pure-callable(mixed): CN
     *
     * @psalm-suppress MixedMethodCall
     */
    public static function construct(string $className): callable
    {
        /** @pslam-var pure-callable(mixed): CN */
        $callable = fn (mixed $value): object => new $className($value);

        return $callable;
    }
}
