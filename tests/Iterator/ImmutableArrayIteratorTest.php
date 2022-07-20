<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Iterator;

use Marvin255\FluentIterable\Iterator\ImmutableArrayIterator;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
class ImmutableArrayIteratorTest extends BaseCase
{
    /**
     * @dataProvider provideIteratorData
     */
    public function testIterator(array $input, array $reference): void
    {
        $immutableIterator = new ImmutableArrayIterator($input);

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

        $immutableIterator = new ImmutableArrayIterator($input);
        $input[] = 'iop';
        $result = [];
        foreach ($immutableIterator as $key => $item) {
            $result[$key] = $item;
        }

        $this->assertSame($reference, $result);
    }

    public function testCount(): void
    {
        $immutableIterator = new ImmutableArrayIterator([1, 2, 3]);

        $result = \count($immutableIterator);

        $this->assertSame(3, $result);
    }
}
