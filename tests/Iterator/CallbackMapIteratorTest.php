<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use ArrayObject;
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
     * @dataProvider provideIteratorData
     */
    public function testIterator(Iterator $iterator, callable $callback, mixed $reference): void
    {
        $immutableIterator = new CallbackMapIterator($iterator, $callback);

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function provideIteratorData(): array
    {
        return [
            'simple iterator' => [
                (new ArrayObject(['q', 'w', 'e']))->getIterator(),
                fn (string $letter): string => "{$letter}a",
                ['qa', 'wa', 'ea'],
            ],
        ];
    }
}
