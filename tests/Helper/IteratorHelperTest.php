<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use ArrayObject;
use Iterator;
use Marvin255\FluentIterable\Helper\IteratorHelper;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class IteratorHelperTest extends BaseCase
{
    /**
     * @psalm-param Iterator<mixed, mixed> $iterator
     * @psalm-param int $reference
     * @dataProvider provideCount
     */
    public function testCount(Iterator $iterator, int $reference): void
    {
        $result = IteratorHelper::count($iterator);

        $this->assertSame($reference, $result);
    }

    public function provideCount(): array
    {
        return [
            'iterator' => [
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                3,
            ],
            'empty iterator' => [
                (new ArrayObject([]))->getIterator(),
                0,
            ],
            'generator' => [
                (function () {
                    yield 'q';
                    yield 'w';
                    yield 'e';
                })(),
                3,
            ],
        ];
    }

    /**
     * @psalm-param Iterator<mixed, mixed> $iterator
     * @psalm-param array $reference
     * @dataProvider provideToArray
     */
    public function testToArray(Iterator $iterator, array $reference): void
    {
        $result = IteratorHelper::toArray($iterator);

        $this->assertSame($reference, $result);
    }

    public function provideToArray(): array
    {
        return [
            'iterator' => [
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                (new ArrayObject([]))->getIterator(),
                [],
            ],
            'string keys iterator' => [
                (new ArrayObject(['q' => 'q', 'w' => 'w', 'e' => 'e']))->getIterator(),
                ['q', 'w', 'e'],
            ],
            'generator' => [
                (function () {
                    yield 'q';
                    yield 'w';
                    yield 'e';
                })(),
                ['q', 'w', 'e'],
            ],
        ];
    }
}
