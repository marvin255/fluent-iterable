# FluentIterable

[![Latest Stable Version](https://poser.pugx.org/marvin255/fluent-iterable/v/stable.png)](https://packagist.org/packages/marvin255/fluent-iterable)
[![Total Downloads](https://poser.pugx.org/marvin255/fluent-iterable/downloads.png)](https://packagist.org/packages/marvin255/fluent-iterable)
[![License](https://poser.pugx.org/marvin255/fluent-iterable/license.svg)](https://packagist.org/packages/marvin255/fluent-iterable)
[![Build Status](https://github.com/marvin255/fluent-iterable/workflows/marvin255_fluent_iterable/badge.svg)](https://github.com/marvin255/fluent-iterable/actions?query=workflow%3A%22marvin255_fluent_iterable%22)

Object that provides `map`, `filter` and other array related functions for any iterable instances (`array`, `Iterator`, `Generator`) in a simple fluent style.

```php
use Marvin255\FluentIterable\FluentIterable;
use Marvin255\FluentIterable\Helper\Reducer;
use Marvin255\FluentIterable\Helper\Filter;
use Marvin255\FluentIterable\Helper\Compare;

$input = [1, 2, 3, 4];
$result = FluentIterable::of($input)
    ->skip(1)
    ->filter(Filter::compare(Compare::LESS_THEN, 4))
    ->map(fn (int $item): int => $item + 1)
    ->reduce(Reducer::sum())
    ->get();
``` 



## Installation

Install via composer:

```bash
composer req marvin255/fluent-iterable
```



## Usage

Initiate item using factory (any `iterable` instances are allowed)

```php
$fluent = FluentIterable::of($input);
```

Apply intermediate methods (`merge`, `filter`, `map`, `skip`, `limit`, `sorted`, `peek`, `distinct`, `flatten`)

```php
$fluent = $fluent->map(fn (int $item): int => $item + 1)
    ->filter(Filter::compare(Compare::LESS_THEN, 4))
    ->skip(1);
```

Get result using one of finalizing methods (`walk`, `reduce`, `findByIndex`, `findOne`, `findFirst`, `findLast`, `toArray`, `getIterator`, `count`, `matchAll`, `matchNone`, `matchAny`)

```php
$result = $fluent->toArray();
```

Methods that convert list to a single item (`reduce`, `findOne`, `findByIndex`, `findFirst`, `findLast`) return an [`Optional`](https://github.com/marvin255/optional) instance.



## Debugging

`peek` method might be used to show intermediate data.

```php
use Marvin255\FluentIterable\FluentIterable;
use Marvin255\FluentIterable\Helper\Reducer;
use Marvin255\FluentIterable\Helper\Filter;
use Marvin255\FluentIterable\Helper\Compare;

$input = [1, 2, 3, 4];
$result = FluentIterable::of($input)
    ->filter(Filter::compare(Compare::LESS_THEN, 3))
    ->peek(
        function (mixed $item): void {
            var_dump($item);
        }
    )
    ->reduce(Reducer::sum())
    ->get();
```

Will print something like

```
int(1)
int(2)
```
