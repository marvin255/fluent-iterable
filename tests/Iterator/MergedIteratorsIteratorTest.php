<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

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
                    $this->createIterator('q', 'w', 'e'),
                    $this->createIterator(1, 2, 3),
                ],
                ['q', 'w', 'e', 1, 2, 3],
            ],
            'mixed' => [
                [
                    $this->createEmptyIterator(),
                    $this->createIterator('q', 'w', 'e'),
                    $this->createEmptyIterator(),
                    $this->createIterator(1, 2, 3),
                    $this->createEmptyIterator(),
                ],
                ['q', 'w', 'e', 1, 2, 3],
            ],
        ];
    }

    public function testCount(): void
    {
        $immutableIterator = new MergedIteratorsIterator(
            [
                $this->createIterator('q', 'w', 'e'),
                $this->createEmptyIterator(),
                $this->createCountableIterator(10),
                $this->createGenerator('y'),
            ]
        );

        $result = \count($immutableIterator);

        $this->assertSame(14, $result);
    }
}
