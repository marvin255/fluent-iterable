<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\HashHelper;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
final class HashHelperTest extends BaseCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideToArray')]
    public function testToArray(mixed $input, string $reference): void
    {
        $result = HashHelper::createHashForData($input);

        $this->assertSame($reference, $result);
    }

    public static function provideToArray(): array
    {
        $obj = new \stdClass();

        return [
            'short string' => [
                'Lorem Ipsum is simply dummy text of',
                'strLorem Ipsum is simply dummy text of',
            ],
            'long string' => [
                'Lorem Ipsum is simply dummy text of1',
                'strf13a541107698241e3ec5ff9f1a68197',
            ],
            'float' => [
                1.11,
                'double1.11',
            ],
            'int' => [
                123,
                'integer123',
            ],
            'true' => [
                true,
                'boolean1',
            ],
            'false' => [
                false,
                'boolean',
            ],
            'array' => [
                [1, '2', 3],
                'cust27897c586d9a88de810ba3407bfa859f',
            ],
            'object' => [
                $obj,
                'obj' . md5(serialize($obj)),
            ],
        ];
    }
}
