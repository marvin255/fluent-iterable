<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

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
     *
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
                $this->createIterator('q', 'w', 'e'),
                3,
            ],
            'empty iterator' => [
                $this->createEmptyIterator(),
                0,
            ],
            'generator' => [
                $this->createGenerator('q', 'w', 'e'),
                3,
            ],
            'countable iterator' => [
                $this->createCountableIterator(11),
                11,
            ],
        ];
    }

    /**
     * @psalm-param Iterator<mixed, mixed> $iterator
     * @psalm-param array $reference
     *
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
                $this->createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                $this->createEmptyIterator(),
                [],
            ],
            'string keys iterator' => [
                $this->createIteratorFromArray(['q' => 'q', 'w' => 'w', 'e' => 'e']),
                ['q', 'w', 'e'],
            ],
            'generator' => [
                $this->createGenerator('q', 'w', 'e'),
                ['q', 'w', 'e'],
            ],
        ];
    }
}
