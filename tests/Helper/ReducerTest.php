<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\Reducer;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class ReducerTest extends BaseCase
{
    /**
     * @dataProvider provideMin
     */
    public function testMin(int|float|null $carry, int|float $input, int|float $reference): void
    {
        $callback = Reducer::min();
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public function provideMin(): array
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
        ];
    }

    /**
     * @dataProvider provideMinParam
     */
    public function testMinParam(string $paramName, array|object|null $carry, array|object $input, array|object $reference): void
    {
        $callback = Reducer::minParam($paramName);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public function provideMinParam(): array
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

    /**
     * @dataProvider provideMax
     */
    public function testMax(int|float|null $carry, int|float $input, int|float $reference): void
    {
        $callback = Reducer::max();
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public function provideMax(): array
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
        ];
    }

    /**
     * @dataProvider provideMaxParam
     */
    public function testMaxParam(string $paramName, array|object|null $carry, array|object $input, array|object $reference): void
    {
        $callback = Reducer::maxParam($paramName);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public function provideMaxParam(): array
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

    /**
     * @dataProvider provideSum
     */
    public function testSum(int|float|null $carry, int|float|null $input, int|float $reference): void
    {
        $callback = Reducer::sum();
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public function provideSum(): array
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

    /**
     * @dataProvider provideJoin
     */
    public function testJoin(?string $separator, int|float|string|null $carry, int|float|string|null $input, string $reference): void
    {
        $callback = $separator === null ? Reducer::join() : Reducer::join($separator);
        $result = $callback($carry, $input);

        $this->assertSame($reference, $result);
    }

    public function provideJoin(): array
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
