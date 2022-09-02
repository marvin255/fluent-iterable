<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackFilterIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
class CallbackFilterIteratorTest extends IteratorCase
{
    /**
     * @psalm-param Iterator<int, mixed> $iterator
     * @psalm-param callable(mixed, int=): bool $callback
     * @psalm-param mixed $reference
     *
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, callable $callback, mixed $reference): void
    {
        $iterator = new CallbackFilterIterator($iterator, $callback);

        $this->assertIteratorContains($reference, $iterator);
    }

    public function provideIteratorData(): array
    {
        return [
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                fn (string $letter): bool => $letter !== 'w',
                ['q', 'e'],
            ],
            'empty iterator' => [
                $this->createEmptyIterator(),
                fn (string $letter): bool => $letter !== 'w',
                [],
            ],
        ];
    }

    /**
     * @psalm-param Iterator<int, mixed> $input
     *
     * @dataProvider provideCountData
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $iterator = new CallbackFilterIterator($input, fn () => false);

        $this->assertCountableCount($reference, $iterator);
    }

    public function provideCountData(): array
    {
        return [
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                3,
            ],
            'generator' => [
                $this->createGenerator('q'),
                1,
            ],
        ];
    }
}
