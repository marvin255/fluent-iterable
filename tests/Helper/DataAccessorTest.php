<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\DataAccessor;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class DataAccessorTest extends BaseCase
{
    /**
     * @dataProvider provideGet
     */
    public function testGet(string $path, array|object $data, mixed $reference): void
    {
        $result = DataAccessor::get($path, $data);

        $this->assertSame($reference, $result);
    }

    public static function provideGet(): array
    {
        $object = new \stdClass();
        $object->test = new \stdClass();
        $object->test->test = 123;

        return [
            'array nested' => [
                'test.test',
                ['test' => ['test' => 123]],
                123,
            ],
            'array top level' => [
                'test',
                ['test' => 123],
                123,
            ],
            "array doesn't exist" => [
                'test.test',
                ['test' => 123],
                null,
            ],
            'object nested' => [
                'test.test',
                $object,
                123,
            ],
            "object doesn't exist" => [
                'test.test1',
                $object,
                null,
            ],
            'object top level' => [
                'test',
                $object,
                $object->test,
            ],
            'path trim' => [
                '..test   ',
                ['test' => 123],
                123,
            ],
        ];
    }
}
