<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Iterator;
use Marvin255\FluentIterable\Iterator\CallbackMapIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class CallbackMapIteratorTest extends BaseCase
{
    /**
     * @psalm-param Iterator<mixed> $iterator
     * @psalm-param callable(mixed): mixed $callback
     * @psalm-param mixed $reference
     *
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, callable $callback, mixed $reference): void
    {
        $iterator = new CallbackMapIterator($iterator, $callback);

        $result = $this->runLoopOnIterator($iterator);

        $this->assertSame($reference, $result);
    }

    public function provideIteratorData(): array
    {
        return [
            'simple iterator' => [
                $this->createIterator('q', 'w', 'e'),
                fn (string $letter): string => "{$letter}a",
                ['qa', 'wa', 'ea'],
            ],
        ];
    }

    /**
     * @dataProvider provideCountData
     */
    public function testCount(Iterator $input, int $reference): void
    {
        $iterator = new CallbackMapIterator($input, fn () => false);

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
            'countable only iterator' => [
                $this->createCountableIterator(2),
                2,
            ],
        ];
    }
}
