<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\FluentIterable\Iterator\PeekIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
final class PeekIteratorTest extends IteratorCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed): mixed $callback
     * @psalm-param mixed $reference
     * @psalm-param mixed $iteratorReference
     *
     * @psalm-suppress MixedArrayAssignment
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideIteratorData')]
    public function testIterator(\Iterator $iterator, mixed $reference, mixed $iteratorReference): void
    {
        $result = [];
        $iterator = new PeekIterator(
            $iterator,
            function (mixed $item, int $key) use (&$result): void {
                $result[$key] = $item;
            }
        );

        $iteratorResult = $this->runLoopOnIterator($iterator);

        $this->assertSame($reference, $result, 'Every item must be iterated');
        $this->assertSame($iteratorReference, $iteratorResult, "Result array mustn't be changed");
    }

    public static function provideIteratorData(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                ['q', 'w', 'e'],
                ['q', 'w', 'e'],
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                [],
                [],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideCountData')]
    public function testCount(\Iterator $input, int $reference): void
    {
        $iterator = new CallbackMapIterator($input, function (): void {});

        $this->assertCountableCount($reference, $iterator);
    }

    public static function provideCountData(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                3,
            ],
            'generator' => [
                self::createGenerator('q'),
                1,
            ],
        ];
    }
}
