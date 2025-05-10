<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackFilterIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
final class CallbackFilterIteratorTest extends IteratorCase
{
    /**
     * @psalm-param Iterator<int, mixed> $iterator
     * @psalm-param callable(mixed, int=): bool $callback
     * @psalm-param array $reference
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideIteratorData')]
    public function testIterator(\Iterator $iterator, callable $callback, array $reference): void
    {
        $iterator = new CallbackFilterIterator($iterator, $callback);

        $this->assertIteratorContains($reference, $iterator);
    }

    public static function provideIteratorData(): array
    {
        return [
            'iterator' => [
                self::createIterator('q', 'w', 'e'),
                fn (string $letter): bool => 'w' !== $letter,
                ['q', 'e'],
            ],
            'empty iterator' => [
                self::createEmptyIterator(),
                fn (string $letter): bool => 'w' !== $letter,
                [],
            ],
        ];
    }

    /**
     * @psalm-param Iterator<int, mixed> $input
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideCountData')]
    public function testCount(\Iterator $input, int $reference): void
    {
        $iterator = new CallbackFilterIterator($input, fn () => false);

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
