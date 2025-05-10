<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\FluentIterableException;
use Marvin255\FluentIterable\Helper\Reducer;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
final class ReducerTest extends BaseCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideMin')]
    public function testMin(int|float|null $carry, int|float $input, int|float $reference): void
    {
        $callback = Reducer::min();
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideMin(): array
    {
        return [
            'empty carry' => [
                null,
                1,
                1,
            ],
            'carry min' => [
                1,
                2,
                1,
            ],
            'carry max' => [
                2,
                1,
                1,
            ],
            'equal' => [
                1,
                1,
                1,
            ],
            'equal should use the first value' => [
                1,
                1.0,
                1,
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideMinParam')]
    public function testMinParam(string $paramName, array|object|null $carry, array|object $input, array|object $reference): void
    {
        $callback = Reducer::minParam($paramName);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideMinParam(): array
    {
        return [
            'empty carry' => [
                'test',
                null,
                ['test' => 1],
                ['test' => 1],
            ],
            'carry min' => [
                'test',
                ['test' => 1],
                ['test' => 2],
                ['test' => 1],
            ],
            'carry max' => [
                'test',
                ['test' => 2],
                ['test' => 1],
                ['test' => 1],
            ],
            'equal' => [
                'test',
                ['test' => 1, 'isCarry' => true],
                ['test' => 1],
                ['test' => 1, 'isCarry' => true],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideMax')]
    public function testMax(int|float|null $carry, int|float $input, int|float $reference): void
    {
        $callback = Reducer::max();
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideMax(): array
    {
        return [
            'empty carry' => [
                null,
                1,
                1,
            ],
            'carry min' => [
                1,
                2,
                2,
            ],
            'carry max' => [
                2,
                1,
                2,
            ],
            'equal' => [
                1,
                1,
                1,
            ],
            'equal should use the first value' => [
                1,
                1.0,
                1,
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideMaxParam')]
    public function testMaxParam(string $paramName, array|object|null $carry, array|object $input, array|object $reference): void
    {
        $callback = Reducer::maxParam($paramName);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideMaxParam(): array
    {
        return [
            'empty carry' => [
                'test',
                null,
                ['test' => 1],
                ['test' => 1],
            ],
            'carry min' => [
                'test',
                ['test' => 1],
                ['test' => 2],
                ['test' => 2],
            ],
            'carry max' => [
                'test',
                ['test' => 2],
                ['test' => 1],
                ['test' => 2],
            ],
            'equal' => [
                'test',
                ['test' => 1, 'isCarry' => true],
                ['test' => 1],
                ['test' => 1, 'isCarry' => true],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideSum')]
    public function testSum(int|float|null $carry, int|float|null $input, int|float $reference): void
    {
        $callback = Reducer::sum();
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideSum(): array
    {
        return [
            'empty carry' => [
                null,
                1,
                1,
            ],
            'empty item' => [
                1,
                null,
                1,
            ],
            'carry' => [
                1,
                1,
                2,
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideSumParam')]
    public function testSumParam(string $paramName, int|float|null $carry, array|object $input, int|float $reference): void
    {
        $callback = Reducer::sumParam($paramName);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideSumParam(): array
    {
        return [
            'empty carry' => [
                'test',
                null,
                ['test' => 1],
                1,
            ],
            'carry' => [
                'test',
                2,
                ['test' => 1],
                3,
            ],
        ];
    }

    public function testSumWrongTypeException(): void
    {
        $callback = Reducer::sumParam('test');

        $this->expectException(FluentIterableException::class);
        $callback(null, ['test' => 'test']);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideJoin')]
    public function testJoin(?string $separator, int|float|string|null $carry, int|float|string|null $input, string $reference): void
    {
        $callback = null === $separator ? Reducer::join() : Reducer::join($separator);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public static function provideJoin(): array
    {
        return [
            'empty carry' => [
                '_',
                null,
                'test',
                'test',
            ],
            'not empty carry' => [
                '_',
                'test',
                'test',
                'test_test',
            ],
            'default separator' => [
                null,
                'test',
                'test',
                'testtest',
            ],
            'join with number' => [
                '_',
                'test',
                123,
                'test_123',
            ],
            'carry number' => [
                '_',
                123,
                123,
                '123_123',
            ],
        ];
    }
}
