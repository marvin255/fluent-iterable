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
     * @dataProvider provideDate
     */
    public function testDate(string $input, ?\DateTimeZone $tz, string $reference): void
    {
        $callback = Mapper::date($tz);
        $result = $callback($input);

        $this->assertSame($reference, $result->format('Y-m-d H:i:s e'));
    }

    /**
     * @dataProvider provideDate
     */
    public function testDateMutable(string $input, ?\DateTimeZone $tz, string $reference): void
    {
        $callback = Mapper::dateMutable($tz);
        $result = $callback($input);

        $this->assertSame($reference, $result->format('Y-m-d H:i:s e'));
    }

    public function provideDate(): array
    {
        $defaultTz = date_default_timezone_get();

        return [
            'string d.m.Y' => [
                '10.01.2023',
                null,
                "2023-01-10 00:00:00 {$defaultTz}",
            ],
            'string d.m.Y H:i:s' => [
                '10.01.2023 10:10:10',
                null,
                "2023-01-10 10:10:10 {$defaultTz}",
            ],
            'string d.m.Y H:i:s and tz' => [
                '10.01.2023 10:10:10',
                new \DateTimeZone('Asia/Yekaterinburg'),
                '2023-01-10 10:10:10 Asia/Yekaterinburg',
            ],
        ];
    }

    /**
     * @dataProvider provideDateToString
     */
    public function testDateToString(\DateTimeInterface $input, string $format, string $reference): void
    {
        $callback = Mapper::dateToString($format);
        $result = $callback($input);

        $this->assertSame($reference, $result);
    }

    public function provideDateToString(): array
    {
        return [
            'd.m.Y' => [
                new \DateTimeImmutable('10.01.2023'),
                'Y-m-d H:i:s',
                '2023-01-10 00:00:00',
            ],
            'd.m.Y H:i:s' => [
                new \DateTimeImmutable('10.01.2023 10:10:10'),
                'Y-m-d H:i:s',
                '2023-01-10 10:10:10',
            ],
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
