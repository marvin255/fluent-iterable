<?php

declare(strict_types=1);

namespace Marvin255\FluentIterable\Tests\Helper;

use Marvin255\FluentIterable\Helper\Order;
use Marvin255\FluentIterable\Helper\Sorter;
use Marvin255\FluentIterable\Tests\BaseCase;

/**
 * @internal
 */
final class SorterTest extends BaseCase
{
    /**
     * @dataProvider provideSortNumeric
     */
    public function testSortNumeric(Order $order, int|float $input1, int|float $input2, int $reference): void
    {
        $callback = Sorter::sortNumeric($order);
        $result = $callback($input1, $input2);

        $this->assertSame($reference, $result);
    }

    public static function provideSortNumeric(): array
    {
        return [
            'asc' => [Order::ASC, 2, 1, 1],
            'desc' => [Order::DESC, 2, 1, -1],
        ];
    }

    /**
     * @dataProvider provideSortNumericParam
     */
    public function testSortNumericParam(string $key, Order $order, array|object $input1, array|object $input2, int $reference): void
    {
        $callback = Sorter::sortNumericParam($key, $order);
        $result = $callback($input1, $input2);

        $this->assertSame($reference, $result);
    }

    public static function provideSortNumericParam(): array
    {
        return [
            'asc' => ['test', Order::ASC, ['test' => 2], ['test' => 1], 1],
            'desc' => ['test', Order::DESC, ['test' => 2], ['test' => 1], -1],
        ];
    }

    /**
     * @dataProvider provideSortString
     */
    public function testSortString(Order $order, string $input1, string $input2, int $reference): void
    {
        $callback = Sorter::sortString($order);
        $result = $callback($input1, $input2);

        $this->assertSame($reference, $result);
    }

    public static function provideSortString(): array
    {
        return [
            'asc' => [Order::ASC, 'b', 'a', 1],
            'desc' => [Order::DESC, 'b', 'a', -1],
        ];
    }

    /**
     * @dataProvider provideSortStringParam
     */
    public function testSortStringParam(string $key, Order $order, array|object $input1, array|object $input2, int $reference): void
    {
        $callback = Sorter::sortStringParam($key, $order);
        $result = $callback($input1, $input2);

        $this->assertSame($reference, $result);
    }

    public static function provideSortStringParam(): array
    {
        return [
            'asc' => ['test', Order::ASC, ['test' => 'b'], ['test' => 'a'], 1],
            'desc' => ['test', Order::DESC, ['test' => 'b'], ['test' => 'a'], -1],
            'empty params' => ['test', Order::ASC, [], [], 0],
        ];
    }
}
