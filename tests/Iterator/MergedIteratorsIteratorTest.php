<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
use InvalidArgumentException;
use Marvin255\FluentIterable\Iterator\MergedIteratorsIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class MergedIteratorsIteratorTest extends BaseCase
{
    public function testConstructWrongTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MergedIteratorsIterator([123]);
    }

    /**
     * @dataProvider provideIteratorData
     */
    public function testIterator(array $input, array $reference): void
    {
        $mergedIterator = new MergedIteratorsIterator($input);

        $result = [];
        foreach ($mergedIterator as $key => $item) {
            $result[$key] = $item;
        }

        $result = [];
        foreach ($mergedIterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideIteratorData(): array
    {
        return [
            'empty input' => [
                [],
                [],
            ],
            'iterators' => [
                [
                    (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                    (new ArrayObject([1, 2, 3]))->getIterator(),
                ],
                ['q', 'w', 'e', 1, 2, 3],
            ],
            'mixed' => [
                [
                    (new ArrayObject([]))->getIterator(),
                    (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                    (new ArrayObject([]))->getIterator(),
                    (new ArrayObject([1, 2, 3]))->getIterator(),
                    (new ArrayObject([]))->getIterator(),
                ],
                ['q', 'w', 'e', 1, 2, 3],
            ],
        ];
    }
}
