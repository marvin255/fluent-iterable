<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\IteratorHelper;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
final class IteratorHelperTest extends BaseCase
{
    /**
     * @psalm-param \Iterator<mixed, mixed> $iterator
     * @psalm-param int $reference
     *
     * @dataProvider provideCount
     */
    public function testCount(\Iterator $iterator, int $reference): void
    {
        $result = IteratorHelper::count($iterator);

        $this->assertSame($reference, $result);
    }

    public static function provideCount(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                3,
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                0,
            ],
            'generator' => [
                self::createGenerator('q', 'w', 'e'),
                3,
            ],
            'countable iterator' => [
                self::createCountableIterator(11),
                11,
            ],
        ];
    }

    /**
     * @psalm-param \Iterator<mixed, mixed> $iterator
     * @psalm-param array $reference
     *
     * @dataProvider provideToArray
     */
    public function testToArray(\Iterator $iterator, array $reference): void
    {
        $result = IteratorHelper::toArray($iterator);

        $this->assertSame($reference, $result);
    }

    public static function provideToArray(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                [],
            ],
            'string keys iterator' => [
                self::createIteratorFromArray(['q' => 'q', 'w' => 'w', 'e' => 'e']),
                ['q', 'w', 'e'],
            ],
            'generator' => [
                self::createGenerator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
        ];
    }

    /**
     * @psalm-param iterable<mixed, mixed> $iterable
     * @psalm-param array $reference
     *
     * @dataProvider provideToArrayIterable
     */
    public function testToArrayIterable(iterable $iterable, array $reference): void
    {
        $result = IteratorHelper::toArrayIterable($iterable);

        $this->assertSame($reference, $result);
    }

    public static function provideToArrayIterable(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                [],
            ],
            'string keys iterator' => [
                self::createIteratorFromArray(['q' => 'q', 'w' => 'w', 'e' => 'e']),
                ['q', 'w', 'e'],
            ],
            'generator' => [
                self::createGenerator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'array' => [
                ['q', 'w', 'e'],
                ['q', 'w', 'e'],
            ],
            'array with keys' => [
                ['q' => 'q', 'w' => 'w', 'e' => 'e'],
                ['q', 'w', 'e'],
            ],
        ];
    }
}
