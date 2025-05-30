<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\FluentIterableException;
use Marvin255\FluentIterable\Iterator\MergedIteratorsIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
final class MergedIteratorsIteratorTest extends IteratorCase
{
    public function testConstructWrongTypeException(): void
    {
        $this->expectException(FluentIterableException::class);
        new MergedIteratorsIterator([123]);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideIteratorData')]
    public function testIterator(array $input, array $reference): void
    {
        $iterator = new MergedIteratorsIterator($input);

        $this->assertIteratorContains($reference, $iterator);
    }

    public static function provideIteratorData(): array
    {
        return [
            'empty input' => [
                [],
                [],
            ],
            'iterators' => [
                [
                    self::createIterator('q', 'w', 'e'),
                    self::createIterator(1, 2, 3),
                ],
                ['q', 'w', 'e', 1, 2, 3],
            ],
            'mixed' => [
                [
                    self::createEmptyIterator(),
                    self::createIterator('q', 'w', 'e'),
                    self::createEmptyIterator(),
                    self::createIterator(1, 2, 3),
                    self::createEmptyIterator(),
                ],
                ['q', 'w', 'e', 1, 2, 3],
            ],
        ];
    }

    public function testCount(): void
    {
        $iterator = new MergedIteratorsIterator(
            [
                self::createIterator('q', 'w', 'e'),
                self::createEmptyIterator(),
                self::createCountableIterator(10),
                self::createGenerator('y'),
            ]
        );

        $this->assertCountableCount(14, $iterator);
    }
}
