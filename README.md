![Pablo García Saldaña - Unsplash (UL) #Y-MGVIkpyFw](https://images.unsplash.com/photo-1441484295955-db07de1fdbad?ixlib=rb-1.2.1&auto=format&fit=crop&w=1280&h=400&q=80)

[![Latest Stable Version](https://poser.pugx.org/darkghosthunter/rut-utils/v/stable)](https://packagist.org/packages/darkghosthunter/rut-utils) [![License](https://poser.pugx.org/darkghosthunter/rut-utils/license)](https://packagist.org/packages/darkghosthunter/rut-utils)
![](https://img.shields.io/packagist/php-v/darkghosthunter/rut-utils.svg) [![Build Status](https://travis-ci.com/DarkGhostHunter/RutUtils.svg?branch=master)](https://travis-ci.com/DarkGhostHunter/RutUtils) [![Coverage Status](https://coveralls.io/repos/github/DarkGhostHunter/RutUtils/badge.svg?branch=master)](https://coveralls.io/github/DarkGhostHunter/RutUtils?branch=master) [![Maintainability](https://api.codeclimate.com/v1/badges/7142cecb93e555cd7028/maintainability)](https://codeclimate.com/github/DarkGhostHunter/RutUtils/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/7142cecb93e555cd7028/test_coverage)](https://codeclimate.com/github/DarkGhostHunter/RutUtils/test_coverage)

# RUT Utilities
 
A complete library for creating, manipulating and generating chilean RUTs or RUNs.

This package allows you to:

- **Create** a RUT object to conveniently hold the RUT information.
- **Validate** and rectify RUTs.
- Flexibly **generate** random RUTs.

While this package works as a fire-and-forget utility for your project, ensure you read the documentation.

## Requirements

This package only needs PHP 7.1.3 and later.

> Optional: Know what *el weón weón weón* means.  

## Installation

Just fire Composer and require it into your project:

```bash
composer require darkghosthunter/rut-utils
```

If you don't have Composer in your project, ~~you should be ashamed~~ you can download this package as a zip and require the `load.php` in the root folder into your project.

## Table of Contents

- [What is a RUT or RUN?](#what-is-a-rut-or-run)
- [Creating a RUT](#creating-a-rut)
  - [Creating multiple RUTs](#creating-multiple-ruts)
- [Retrieving the RUT](#retrieving-a-rut)
  - [Lowercase or Uppercase `K`](#lowercase-or-uppercase-k)
- [Generating random RUTs](#generating-random-ruts)
  - [Generating random unique RUTs](#generating-random-unique-ruts)
- [Validating RUT](#validating-rut)
- [Filter RUTs](#filter-ruts)
- [Compare RUT](#compare-rut-if-is-equal)
- [Rectify (from a RUT Number)](#rectify-from-a-rut-number)
- [Check RUT person or company](#check-rut-person-or-company)
- [Global helper functions](#global-helper-functions)

## Usage

### What is a RUT or RUN?

A RUT (or RUN for people) is a string of numbers which identify a person or company. They're are unique for each one, and they're never re-assigned, so the registry of RUTs is always growing upwards.

The RUT its comprised of a random Number, like `12.345.678`, and a Verification Digit, which is the result of a mathematical algorithm [(Modulo 11)](https://www.google.com/search?q=modulo+11+algorithm) over that number. This Verification Digit vary between `0` and `9`, or a `K`.

This information identifying the person is usually a safe bet for chilean companies. It allows to attach one account to one person (or company), and can be cross-referenced with other data the user may have available through other services.

> What's the difference between RUTs and RUNs? RUT are meant for identifying a person or company taxes, RUNs are to identify persons. But in any case, they're practically the same.

### Creating a RUT

To create a RUT, simple create a new `Rut` or use the `make()` static helper:

```php
<?php 

namespace App;

use DarkGhostHunter\RutUtils\Rut;


// Create a RUT using its numbers and verification digit separately.
$rutA = new Rut('14328145', 0); 

// You can also use the whole string.
$rutB = new Rut('14.328.145-0');

// And even malformed ones with invalid characters.
$rutC = new Rut('asdwdasd14.32.814.5-0');

// And you can use the `make()` static helper for one single string
$rutD = Rut::make('asdwdasd14.32.814.5-0');
```

`Rut` will automatically clean the string and parse the number and verification digit for you, so you don't have to.

Creating this object in these ways won't validate the RUT. Don't worry, we will see more ways to create RUTs in the next sections.

#### Creating multiple RUTs

Sometimes is cumbersome to do a `foreach` or a `for` loop to make RUTs. Instead, use the `make()` static method, which accepts an array to transform multiple strings as RUTs.

```php
<?php 

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Create just one RUT 
$rut = Rut::make('143281450'); 

// Create multiple RUTs
$rutsA = Rut::make('14.328.145-0', '14.328.145-0');

// Or issue an array of multiple RUTs
$rutsB = Rut::make([
    '14.328.145-0',
    '7976228-8',
]);
```

### Retrieving a RUT

Since there is no way to know how your application works with RUTs, you can treat the `Rut` object as an array, object or string for your convenience.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Let's create first the RUT:
$rut = Rut::make('143281450');

// Return the RUT as a string
echo $rut; // 14.328.145-0

// You can get number or verification digit as an array
echo $rut['num']; // 14328145
echo $rut['vd']; // 0

// ...or as an object
echo $rut->num; // 14328145
echo $rut->vd; // 0
``` 

If you need to set a Number or Verification Digit, use `setNum()` and `setVd()`, respectively.

#### Lowercase or Uppercase `K`

A RUT can have the `K` character as verification *digit*. The `Rut` object doesn't discerns between lowercase `k` or uppercase `K` when creating one, but **it always stores uppercase as default**.

You can change this behaviour for all `Rut` instances using the `allLowercase()` or `allUppercase()` methods:

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

Rut::allLowercase();

echo Rut::make('12343580-K')->vd; // "k"

Rut::allUppercase();

echo Rut::make('12343580-K')->vd; // "K"
```

This may come in handy when your source of truth manages lowercase `k`.

> Ensure you set this before making RUTs, as the object will parse the RUT on input, not on output. 

### Generating random RUTs

Sometimes is handy to create RUT on the fly -usually for testing purposes.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::generate();
```

The default mode makes a RUT for normal people, which are bound between 1.000.000 and 30.000.000. You can use the `forCompany()` method, which will vary the result randomly between 50.000.000 and 100.000.000.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::asPerson()->generate();
$company = Rut::asCompany()->generate();
```

Of course one may be not enough. You can add a parameter to these methods with the number of RUTs you want to make. The result will be returned as an `array`.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$peopleRuts = Rut::asPerson()->generate(10);
$companyRuts = Rut::asCompany()->generate(35);
```

If for some reason you need them as raw strings instead of `RUT` instances -which is very good when generating  thousands of them- use the `asRaw()` method.

This will output the random strings like `22605071K`.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$peopleRuts = Rut::asPerson()->asRaw()->generate(10);
$companyRuts = Rut::asCompany()->asRaw()->generate(35);
```

#### Generating random unique RUTs

If you need to create millions of RUTs without the risk of having them repeated, use these methods with `unique()`.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$peopleRuts = Rut::forPerson()->unique()->generate(100000);
$companyRuts = Rut::forCompany()->unique()->generate(100000);
```

### Validating RUT

If your application receives an input and needs to check if the RUT is valid, you can do it with `validate()` which receives a raw string.

You can issue more than one RUT. In that case, it will return `true` if all the RUTs are valid, and `false` if at least one is invalid.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Receive the raw string, and validate it
echo Rut::validate('14328145-0'); // true
echo Rut::validate('14.328.145-0', '12343580-K'); // true
echo Rut::validate(143281450); // true
echo Rut::validate('not-a-rut'); // false
echo Rut::validate(143281450, 'not-a-rut'); // false
```

Alternatively, you can use the `Rut` object itself (which cleans any non-RUT character).

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Cleans the rut, and validate it 
echo Rut::make('14328145-0')->isValid(); // true
echo Rut::make('94.328.145-0')->isValid(); // false
echo Rut::make('cleanthis14328145-0-deletethis')->isValid(); // true
```

You can use this to check if the user has responded with a valid RUT, and process the Request if it is.

#### Strict validation

You can strictly validate a RUT. The RUT being passed must have the Number with thousand separator and hyphen preceding the Verification Digit. 

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Receive the raw string, and strictly validate it
echo Rut::validateStrict('14328145-0'); // false
echo Rut::validateStrict('14.328.145-0', '12343580-K'); // false
echo Rut::validateStrict(143281450); // false
echo Rut::validateStrict('not-a-rut'); // false
echo Rut::validateStrict(143281450, 'not-a-rut'); // false
```

### Filter RUTs

If you get more than one RUT, you can filter only the valid ones using `filter()`, which will take multiple RUTs and return an array comprised of only the valid ones.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Cleans the rut, and validate it 
$rutsA = Rut::filter('14328145-0', '12343580-K', '94.328.145-0', 'not-a-rut');

var_dump($rutsA);

// array(1) {
//     [0] => 14328145-0
//     [1] => 12343580-K
// } 

$rutsB = Rut::filter([
    '14328145-0',
    '12343580-K',
    '94.328.145-0',
    'not-a-rut'
]);

var_dump($rutsB);

// array(1) {
//     [0] => 14328145-0
//     [1] => 12343580-K
// } 
```

You can use this when receiving multiple RUTs from the user Input and you need to process only those that are valid.

### Compare RUT if is equal

Instead of using `$rutA === $rutB`, you can call `isEqual()` which will clean the strings and return if both are equal RUT strings.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Determine if both cleaned RUTs are equal 
$ruts = Rut::areEqual('thisisARut12343580-K', '12343580-K');

echo $ruts; // true
```

You can use this for comparing the user input with the one in the database or other source of truth.

> This doesn't ensure these are valid, just if they're equal.

### Rectify (from a RUT Number)

You may have a RUT without Verification Digit, or you may need the correct one from a whole RUT. In any case, you can use `rectify()` and pass down only the **RUT Number**, in return you will get valid Rut. 

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

// Creates a RUT instance from a number without Verification Digit 
$rut = Rut::rectify('12343580');

echo $rut; // 12.343.580-0
```

> If you pass down a whole RUT, you may get a new RUT with other Verification Digit. Ensure you pass down only the RUT Number. 

### Check RUT person or company

While there is no formal way to know if a particular RUT is for a normal Person or a Company, you can guess is using `isPerson()` or `isCompany()` methods.

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::make('22605071-k');

echo $rut->person(); // true
echo $rut->company(); // false

echo Rut::isPerson('14328145-0'); // true
echo Rut::isCompany('14328145-0'); // false
```

What this does is basically return if the RUT is between 1.000.000 and 50.000.000 for normal people, and between 50.000.001 and 100.000.000 for companies, as *usually* are registered.

### Global helper functions

For convenience, this package includes a set of globally accessible functions.

| Function | Alias for
|---|---
| is_rut() | RutHelper::validate()
| is_rut_strict() | RutHelper::validateStrict()
| is_rut_person() | RutHelper::isPerson()
| is_rut_company() | RutHelper::isCompany() 
| is_rut_equal() | RutHelper::areEqual()
| rut_filter() | RutHelper::filter()
| rut_rectify() | RutHelper::rectify()

### Serialization

RUT are JSON and array serialized with the `num` and `vd` separated, like so:

```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::make('22605071-k');

echo json_encode($rut); // {"num":"22605071","vd":"K"}
print_r($rut->toArray()); // ['num' => 22605071, 'vd' => 'K']
``` 

You can serialize the RUT as a string. Here you have flexibility to use one of the formatting methods:

* `full`: Default option. Serializes with thousand separator.
* `basic`: No thousand separator, only the hyphen.
* `raw`: No thousand separator nor hyphen.


```php
<?php

namespace App;

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::make('22605071-k');

Rut::setStringFormat('full'); 

echo (string)$rut; // "22.605.071-K"

Rut::setStringFormat('basic'); 

echo (string)$rut; // "22605071-K"

Rut::setStringFormat('raw'); 

echo (string)$rut; // "22605071K"
``` 

## License

This package is licenced by the [MIT License](LICENSE).