<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\Iterator\ImmutableArrayIterator;
use Marvin255\FluentIterable\Tests\IteratorCase;

/**
 * @internal
 */
class ImmutableArrayIteratorTest extends IteratorCase
{
    /**
     * @dataProvider provideIteratorData
     */
    public function testIterator(array $input, array $reference): void
    {
        $iterator = new ImmutableArrayIterator($input);

        $this->assertIteratorContains($reference, $iterator);
    }

    public function provideIteratorData(): array
    {
        return [
            'flat array' => [
                ['q', 'w', 'e', 1, 2, 3],
                ['q', 'w', 'e', 1, 2, 3],
            ],
            'string keys array' => [
                ['key' => 0, 'key 1' => 1],
                [0, 1],
            ],
            'nested array' => [
                [1, [2, 3]],
                [1, [2, 3]],
            ],
            'empty array' => [
                [],
                [],
            ],
        ];
    }

    public function testImmutability(): void
    {
        $reference = $input = ['qwe', 'asd', 'xcv'];

        $iterator = new ImmutableArrayIterator($input);
        $input[] = 'iop';
        $result = [];
        foreach ($iterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function testCount(): void
    {
        $iterator = new ImmutableArrayIterator([1, 2, 3]);

        $this->assertCountableCount(3, $iterator);
    }
}
