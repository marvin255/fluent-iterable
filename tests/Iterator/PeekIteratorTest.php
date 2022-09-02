<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\FluentIterable\Iterator\PeekIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class PeekIteratorTest extends BaseCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed): mixed $callback
     * @psalm-param mixed $reference
     * @psalm-param mixed $iteratorReference
     *
     * @dataProvider provideIteratorData
     *
     * @psalm-suppress MixedArrayAssignment
     */
    public function testIterator(Iterator $iterator, mixed $reference, mixed $iteratorReference): void
    {
        $result = [];
        $iterator = new PeekIterator(
            $iterator,
            function (mixed $item, int $key) use (&$result): void {
                $result[$key] = $item;
            }
        );

        foreach ($iterator as $key => $item) {
        }

        $iteratorResult = [];
        foreach ($iterator as $key => $item) {
            $iteratorResult[$key] = $item;
        }

        $this->assertSame($reference, $result, 'Every item must be iterater');
        $this->assertSame($iteratorReference, $iteratorResult, "Result array mustn't be changed");
    }

    public function provideIteratorData(): array
    {
        return [
            'iterator' => [
                $this->createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                $this->createEmptyIterator(),
                [],
                [],
            ],
        ];
    }

    /**
     * @dataProvider provideCountData
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $iterator = new CallbackMapIterator($input, function (): void {});

        $result = \count($iterator);

        $this->assertSame($reference, $result);
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
