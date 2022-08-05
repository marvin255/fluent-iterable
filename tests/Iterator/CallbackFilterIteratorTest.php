<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackFilterIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class CallbackFilterIteratorTest extends BaseCase
{
    /**
     * @psalm-param Iterator<int, mixed> $iterator
     * @psalm-param callable(mixed, int=): bool $callback
     * @psalm-param mixed $reference
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, callable $callback, mixed $reference): void
    {
        $iterator = new CallbackFilterIterator($iterator, $callback);

        $result = [];
        foreach ($iterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideIteratorData(): array
    {
        return [
            'iterator' => [
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                fn (string $letter): bool => $letter !== 'w',
                ['q', 'e'],
            ],
            'empty iterator' => [
                (new ArrayObject([]))->getIterator(),
                fn (string $letter): bool => $letter !== 'w',
                [],
            ],
            'generator' => [
                (function () {
                    yield 'q';
                    yield 'w';
                    yield 'e';
                })(),
                fn (string $letter): bool => $letter !== 'w',
                ['q', 'e'],
            ],
        ];
    }

    /**
     * @psalm-param Iterator<int, mixed> $input
     * @dataProvider provideCountData
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $iterator = new CallbackFilterIterator($input, fn () => false);

        $result = \count($iterator);

        $this->assertSame($reference, $result);
    }

    public function provideCountData(): array
    {
        return [
            'iterator' => [
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                3,
            ],
            'generator' => [
                (function () {
                    yield 'q';
                })(),
                1,
            ],
        ];
    }
}