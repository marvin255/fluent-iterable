<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\Compare;
use Marvin255\FluentIterable\Helper\Filter;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class FilterTest extends BaseCase
{
    /**
     * @dataProvider provideCompare
     */
    public function testCompare(Compare $operator, mixed $operand, mixed $input, bool $reference): void
    {
        $callback = Filter::compare($operator, $operand);
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideCompare(): array
    {
        return [
            'equal' => [
                Compare::EQUAL,
                1,
                1,
                true,
            ],
            'equal negative' => [
                Compare::EQUAL,
                1,
                2,
                false,
            ],
            'not equal' => [
                Compare::NOT_EQUAL,
                1,
                2,
                true,
            ],
            'not equal negative' => [
                Compare::NOT_EQUAL,
                1,
                1,
                false,
            ],
            'greater than' => [
                Compare::GREATER_THAN,
                0,
                1,
                true,
            ],
            'greater than negative' => [
                Compare::GREATER_THAN,
                1,
                1,
                false,
            ],
            'greater than or equal' => [
                Compare::GREATER_THAN_OR_EQUAL,
                1,
                2,
                true,
            ],
            'greater than or equal negative' => [
                Compare::GREATER_THAN_OR_EQUAL,
                2,
                1,
                false,
            ],
            'greater than or equal equal' => [
                Compare::GREATER_THAN_OR_EQUAL,
                1,
                1,
                true,
            ],
            'less than' => [
                Compare::LESS_THEN,
                2,
                1,
                true,
            ],
            'less than negative' => [
                Compare::LESS_THEN,
                1,
                1,
                false,
            ],
            'less than or equal' => [
                Compare::LESS_THEN_OR_EQUAL,
                2,
                1,
                true,
            ],
            'less than or equal negative' => [
                Compare::LESS_THEN_OR_EQUAL,
                1,
                2,
                false,
            ],
            'less than or equal equal' => [
                Compare::LESS_THEN_OR_EQUAL,
                1,
                1,
                true,
            ],
        ];
    }

    /**
     * @dataProvider provideNotNull
     */
    public function testNotNull(mixed $input, bool $reference): void
    {
        $callback = Filter::notNull();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideNotNull(): array
    {
        return [
            'null' => [null, false],
            'not null' => [1, true],
        ];
    }

    /**
     * @dataProvider provideNotEmpty
     */
    public function testNotEmpty(mixed $input, bool $reference): void
    {
        $callback = Filter::notEmpty();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideNotEmpty(): array
    {
        return [
            'empty' => [0, false],
            'empty string' => ['', false],
            'empty array' => [[], false],
            'null' => [null, false],
            'not empty' => [1, true],
        ];
    }

    /**
     * @dataProvider provideRegexp
     */
    public function testRegexp(string $regexp, string $input, bool $reference): void
    {
        $callback = Filter::regexp($regexp);
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideRegexp(): array
    {
        return [
            'regexp' => ['/[0-1]+/', '1', true],
            'regexp negative' => ['/[0-1]+/', 'a', false],
        ];
    }
}
