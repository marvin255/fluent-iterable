# FluentIterable

[![Latest Stable Version](https://poser.pugx.org/marvin255/fluent-iterable/v/stable.png)](https://packagist.org/packages/marvin255/fluent-iterable)
[![Total Downloads](https://poser.pugx.org/marvin255/fluent-iterable/downloads.png)](https://packagist.org/packages/marvin255/fluent-iterable)
[![License](https://poser.pugx.org/marvin255/fluent-iterable/license.svg)](https://packagist.org/packages/marvin255/fluent-iterable)
[![Build Status](https://github.com/marvin255/fluent-iterable/workflows/marvin255_fluent_iterable/badge.svg)](https://github.com/marvin255/fluent-iterable/actions?query=workflow%3A%22marvin255_fluent_iterable%22)

Interface which provides `map`, `filter` and other array related functions for any iterable instance (`array`, `Iterator`, `Generator`) in simple fluent style.

E.g.

```php
$input = new \ArrayObject([1, 2, 3, 4]);
$result = \Marvin255\FluentIterable\FluentIterable::of($input)
    ->skip(1)
    ->filter(fn (int $item): bool => $item < 4)
    ->map(fn (int $item): int => $item + 1)
    ->reduce(fn (int $carry, int $item): int => $carry + $item, 0)
    ->get();
``` 



## Installation

Install via composer:

```bash
composer req marvin255/fluent-iterable
```



## Usage

Initiate item using factory (any `iterable` instance is allowed)

```php
$fluent = \Marvin255\FluentIterable\FluentIterable::of($input);
```

Apply any number of intermediate methods (`merge`, `filter`, `map`, `skip`, `limit`, `sorted`, `peek`, `distinct`, `flatten`)

```php
$fluent = $fluent->map(fn (int $item): int => $item + 1)
    ->filter(fn (int $item): bool => $item < 4)
    ->skip(1);
```

Get result using one of finalizing methods (`walk`, `reduce`, `findByIndex`, `findOne`, `findFirst`, `findLast`, `toArray`, `getIterator`, `count`, `matchAll`, `matchNone`, `matchAny`)

```php
$result = $fluent->toArray();
```

Methods that convert list to a single item (`reduce`, `findOne`, `findByIndex`, `findFirst`, `findLast`) return an [`Optional`](https://github.com/marvin255/optional) instance.



## Debugging

You can use `peek` method to show intermediate data. E.g. 

```php
$input = new \ArrayObject([1, 2, 3, 4]);
$result = \Marvin255\FluentIterable\FluentIterable::of($input)
    ->filter(fn (int $item): bool => $item < 3)
    ->peek(
        function (mixed $item): void {
            var_dump($item);
        }
    )
    ->reduce(fn (int $carry, int $item): int => $carry + $item, 0)
    ->get();
```

Will print something like

```
int(1)
int(2)
```
