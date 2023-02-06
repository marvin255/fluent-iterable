<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\Mapper;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class MapperTest extends BaseCase
{
    /**
     * @dataProvider provideInt
     */
    public function testInt(mixed $input, int $reference): void
    {
        $callback = Mapper::int();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideInt(): array
    {
        return [
            'string' => ['123', 123],
            'int' => [321, 321],
            'null' => [null, 0],
        ];
    }

    /**
     * @dataProvider provideFloat
     */
    public function testFloat(mixed $input, float $reference): void
    {
        $callback = Mapper::float();
        $result = $callback($input);

        $this->assertLessThan(0.0001, abs($reference - $result));
    }

    public function provideFloat(): array
    {
        return [
            'string' => ['1.1', 1.1],
            'float' => [1.1, 1.1],
            'null' => [null, 0],
        ];
    }

    /**
     * @dataProvider provideBool
     */
    public function testBool(mixed $input, bool $reference): void
    {
        $callback = Mapper::bool();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideBool(): array
    {
        return [
            'string 1' => ['1', true],
            'string 0' => ['0', false],
            'int 1' => [1, true],
            'int 0' => [0, false],
            'bool' => [false, false],
            'null' => [null, false],
        ];
    }

    /**
     * @dataProvider provideString
     */
    public function testString(mixed $input, string $reference): void
    {
        $callback = Mapper::string();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideString(): array
    {
        return [
            'string' => ['1', '1'],
            'int' => [0, '0'],
            'null' => [null, ''],
        ];
    }

    /**
     * @dataProvider provideUpperCase
     */
    public function testUpperCase(mixed $input, string $reference): void
    {
        $callback = Mapper::upperCase();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideUpperCase(): array
    {
        return [
            'upper case string' => ['TEST', 'TEST'],
            'lower case string' => ['test', 'TEST'],
            'int' => [123, '123'],
        ];
    }

    /**
     * @dataProvider provideLowerCase
     */
    public function testLowerCase(mixed $input, string $reference): void
    {
        $callback = Mapper::lowerCase();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideLowerCase(): array
    {
        return [
            'upper case string' => ['TEST', 'test'],
            'lower case string' => ['test', 'test'],
            'int' => [123, '123'],
        ];
    }

    /**
     * @dataProvider provideTrim
     */
    public function testTrim(mixed $input, string $reference): void
    {
        $callback = Mapper::trim();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideTrim(): array
    {
        return [
            'trimmed string' => ['test', 'test'],
            'non trimmed string' => [" \n\r\t\v\x00test \n\r\t\v\x00", 'test'],
            'int' => [123, '123'],
        ];
    }

    /**
     * @dataProvider provideLtrim
     */
    public function testLtrim(mixed $input, string $reference): void
    {
        $callback = Mapper::ltrim();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideLtrim(): array
    {
        return [
            'trimmed string' => ['test', 'test'],
            'non trimmed string' => [" \n\r\t\v\x00test \n\r\t\v\x00", "test \n\r\t\v\x00"],
            'int' => [123, '123'],
        ];
    }

    /**
     * @dataProvider provideRtrim
     */
    public function testRtrim(mixed $input, string $reference): void
    {
        $callback = Mapper::rtrim();
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideRtrim(): array
    {
        return [
            'trimmed string' => ['test', 'test'],
            'non trimmed string' => [" \n\r\t\v\x00test \n\r\t\v\x00", " \n\r\t\v\x00test"],
            'int' => [123, '123'],
        ];
    }

    /**
     * @dataProvider providePluck
     */
    public function testPluck(string $key, mixed $default, array|object $input, mixed $reference): void
    {
        $callback = Mapper::pluck($key, $default);
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function providePluck(): array
    {
        $obj = new \stdClass();
        $obj->test = 123;

        return [
            'array' => [
                'test',
                null,
                ['test' => 123],
                123,
            ],
            'array default' => [
                'test',
                'default',
                [],
                'default',
            ],
            'object' => [
                'test',
                null,
                $obj,
                123,
            ],
            'object default' => [
                'test1',
                'default',
                $obj,
                'default',
            ],
        ];
    }
}
