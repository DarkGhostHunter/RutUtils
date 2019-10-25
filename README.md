![Pablo García Saldaña - Unsplash (UL) #Y-MGVIkpyFw](https://images.unsplash.com/photo-1441484295955-db07de1fdbad?ixlib=rb-1.2.1&auto=format&fit=crop&w=1280&h=400&q=80)

[![Latest Stable Version](https://poser.pugx.org/darkghosthunter/rut-utils/v/stable)](https://packagist.org/packages/darkghosthunter/rut-utils) [![License](https://poser.pugx.org/darkghosthunter/rut-utils/license)](https://packagist.org/packages/darkghosthunter/rut-utils)
![](https://img.shields.io/packagist/php-v/darkghosthunter/rut-utils.svg) [![Build Status](https://travis-ci.com/DarkGhostHunter/RutUtils.svg?branch=master)](https://travis-ci.com/DarkGhostHunter/RutUtils) [![Coverage Status](https://coveralls.io/repos/github/DarkGhostHunter/RutUtils/badge.svg?branch=master)](https://coveralls.io/github/DarkGhostHunter/RutUtils?branch=master) [![Maintainability](https://api.codeclimate.com/v1/badges/7142cecb93e555cd7028/maintainability)](https://codeclimate.com/github/DarkGhostHunter/RutUtils/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/7142cecb93e555cd7028/test_coverage)](https://codeclimate.com/github/DarkGhostHunter/RutUtils/test_coverage)

# RUT Utilities
 
A complete library for creating, manipulating and generating chilean RUTs or RUNs.

This package allows you to:

- **Create** a RUT object to conveniently hold the RUT information.
- **Validate**, clean and rectify RUTs.
- **Generate** random RUTs in a flexible manner.

While this package works as a fire-and-forget utility for your project, ensure you read the documentation so you don't repeat yourself.

## Requirements

This package only needs PHP 7.2 and later.

It may work on older versions, but it will only support active PHP releases. 

> Optional: Know what *el weón weón weón* means.  

## Installation

Just fire Composer and require it into your project:

```bash
composer require darkghosthunter/rut-utils
```

If you don't have Composer in your project, ~~you should be ashamed~~ just [install it](https://getcomposer.org/download/) .

## Usage

* [What is a RUT or RUN?](#what-is-a-rut-or-run)
* [Creating a RUT](#creating-a-rut)
* [Retrieving a RUT](#retrieving-a-rut)
* [Generating RUTs](#generating-ruts)
* [Helpers](#helpers)
* [Make Callbacks](#make-callbacks)
* [Serialization](#serialization)
* [Global helper](#global-helper)

### What is a RUT or RUN?

A RUT (or RUN for people) is a string of numbers which identify a person or company. They're unique for each one, and they're never re-assigned to an individual, so the registry of RUTs is always growing.

The RUT its comprised of a random Number, like `18.765.432`, and a Verification Digit, which is the result of a mathematical algorithm [(Modulo 11)](https://www.google.com/search?q=modulo+11+algorithm) over that number. This Verification Digit vary between `0` and `9`, or a `K`. In the end. you get this:

```
18.765.432-1
```

This identification information is a safe bet for chilean companies. It allows to attach one account to one individual (person or company), and can be cross-referenced with other data the user may have available through other services.

> What's the difference between RUTs and RUNs? RUT are meant for identifying a person or company taxes, RUNs are to identify single persons. For both cases, **they're practically the same**.

### Creating a RUT

There are two ways to create a RUT: manual instancing, which is strict, and using the `make()` static helper.

Using manual instantiation allows you to create a RUT by the given number and verification digit quickly.

```php
<?php 

use DarkGhostHunter\RutUtils\Rut;

// Create a RUT using its numbers and verification digit separately.
$rutA = new Rut('14328145', 0); 

// ...even if the RUT is malformed 
$rutB = new Rut(10000, 'foo'); 
```

While this is a very good way to have a rut when knowing it's valid, you may want to use the `make()` static helper to create a Rut instance.

```php
<?php 

use DarkGhostHunter\RutUtils\Rut;

// Create a RUT using its numbers and verification digit separately.
$rutA = Rut::make('14328145', 0);

// You can also use a whole string.
$rutB = Rut::make('14.328.145-0');

// And even malformed ones with invalid characters
$rutC = Rut::make('asdwdasd14.32.814.5-0');
```

The static helper will automatically clean the string and parse the number and verification digit for you, so you don't have to.

If the resulting Rut is empty, `null` will be returned instead of a Rut instance, which you can use to quickly set flow control in your code.

```php
<?php 

use DarkGhostHunter\RutUtils\Rut;

$malformed = Rut::make('not-a-rut');

if (is_null($malformed)) {
    echo 'This Rut is malformed!';
}
```

Creating this object in these ways **won't check if the RUT is valid**. Don't worry, we will see more ways to create RUTs in the next sections.

#### Creating a valid Rut

Let's say your user is issuing a RUT in your application, and you need to validate it before proceeding. You can easily create a RUT or return something else with the `makeOr()`, which accepts a value or callable that will be returned when the RUT is malformed or invalid.

```php
<?php 

use DarkGhostHunter\RutUtils\Rut;

$validA = Rut::makeOr('14328145', 0, 'this is valid');

echo $validA; // "14.328.145-0"

$validB = Rut::makeOr('14.328.145-0', function () {
    return 'also valid'; 
});

echo $validA; // "14.328.145-0"

$invalid = Rut::makeOr('18.765.432-1', null, 'this is invalid');

echo $invalid; // "this is invalid"
```

Alternatively, you may want to use the `makeOrThrow()` to throw an exception when trying to make a malformed or invalid RUT.

```php
<?php 

use DarkGhostHunter\RutUtils\Rut;

$validA = Rut::makeOrThrow('18.765.432', 1);

// [!] [InvalidRutException]
```

#### Creating multiple RUTs

It can be cumbersome to do a `foreach` or `for` loop to make multiple RUTs. Instead of that, use the `many()` static method. The method will automatically filter malformed RUTs from the final array.

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

// Create multiple RUTs
$rutsA = Rut::many('14.328.145-0', '14.328.145-0');

// Or issue an array of multiple RUTs
$rutsB = Rut::many([
    '14.328.145-0',
    '7976228-8',
    ['14.328.145', 0]
]);
```

You can use the `manyOrThrow()` to return an exception in case a malformed or invalid RUT is detected.

### Retrieving a RUT

Since there is no way to know how your application works with RUTs, you can treat the `Rut` object as an array, object or string for your convenience.

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

// Let's create first the RUT:
$rut = Rut::make(14328145, 0);

// Return the RUT as a string
echo $rut; // 14.328.145-0

// You can get number or verification digit as an array
echo $rut['num']; // 14328145
echo $rut['vd']; // 0

// ...or as an object
echo $rut->num; // 14328145
echo $rut->vd; // 0
``` 

> For safety reasons, you cannot set the `num` and `vd` in the instance. 

#### Lowercase or Uppercase `K`

A RUT can have the `K` character as verification *digit*. The Rut object doesn't discerns between lowercase `k` or uppercase `K` when creating one, but **it always stores uppercase as default**.

You can change this behaviour for all Rut instances using the `allUppercase()` or `allLowercase()` methods:

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

Rut::allLowercase();

echo Rut::make('12343580', 'K')->vd; // "k"

Rut::allUppercase();

echo Rut::make('12343580', 'K')->vd; // "K"
```

Additionally, you can change thins configuration for a single instance by using `uppercase()` and `lowercase()`.

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::make('12343580', 'K');

$rut->lowercase();

echo $rut->vd; // "K"

$rut->uppercase();

echo $rut->vd; // "k"
```

This may come in handy when your source of truth manages lowercase `k` and you need strict comparison or storing mechanisms.

### Generating RUTs

Sometimes is handy to create a RUT on the fly, usually for testing purposes when seeding and mocking.

You can do that using the `RutGenerator` class and use the methods to build how you want to generate your RUTs. The methods are fluent, meaning, you can chain them, until you use the `generate()` method.

```php
<?php

use DarkGhostHunter\RutUtils\RutGenerator;

$rut = RutGenerator::make()->generate();

echo $rut; // "7.976.228-8" 
```

The default mode makes a RUT for normal people, which are bound between 1.000.000 and 50.000.000. You can use the `forCompany()` method, which will vary the result randomly between 50.000.000 and 100.000.000.

```php
<?php

use DarkGhostHunter\RutUtils\RutGenerator;

echo $rut = RutGenerator::make()->asPerson()->generate();
// "15.846.327-K"

echo $company = RutGenerator::make()->asCompany()->generate();
// "54.029.467-4"
```

Of course one may be not enough. You can add a parameter to these methods with the number of RUTs you want to make. The result will be returned as an `array`.

```php
<?php

use DarkGhostHunter\RutUtils\RutGenerator;

$peopleRuts = RutGenerator::make()->asPerson()->generate(10);
$companyRuts = RutGenerator::make()->asCompany()->generate(35);
```

If for some reason you need them as raw strings instead of Rut instances, which is very good when generating thousands of them on strict memory usage, use the `asBasic()` and `asRaw()` method.

This will output the random strings like `22605071K`.

```php
<?php

use DarkGhostHunter\RutUtils\RutGenerator;

$raw = RutGenerator::make()->asRaw()->generate(10);
$basic = RutGenerator::make()->asBasic()->generate(20);
$strict = RutGenerator::make()->asStrict()->generate(30);
```

#### Generating random unique RUTs

If you need to create more than thousands of RUTs without the risk of having them duplicated, use the `unique()` method.

```php
<?php

use DarkGhostHunter\RutUtils\RutGenerator;

$ruts = RutGenerator::make()->unique()->generate(100000);
```

##### Unique results

You may have a custom seeder in your application that may call  `generate()` every single time, increasing risk of collisions with each generation. Fear not! Using the `generateStatic()` you are guaranteed to get unique results during a single application lifecycle.

```php
<?php

use DarkGhostHunter\RutUtils\RutGenerator;

$users = [
    ['name' => 'John'],
    ['name' => 'Clara'],
    ['name' => 'Mark'],
    // ... and other 99.997 records
];

$seeder = function ($user) {
    return array_merge($user, [
        'rut' => RutGenerator::make()->generateStatic()
    ]);
};

// Call the seeder
foreach ($users as $key => $user) {
    $users[$key] = $seeder($user);
}
```

### Helpers

You can manipulate and check strings quickly using the `RutHelper` class, which contains a wide variety of handy static methods you can use.

* [`cleanRut`](#cleanRut)
* [`separateRut`](#separateRut)
* [`validate`](#validate)
* [`validateStrict`](#validateStrict)
* [`filter`](#filter)
* [`rectify`](#rectify)
* [`isPerson`](#isPerson)
* [`isCompany`](#isCompany)
* [`isEqual`](#isEqual)
* [`getVd`](#getVd)

#### `cleanRut`

Clears a RUT string from invalid characters. Additionally, you can set if you want the `K` verification character as uppercase or lowercase.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

echo RutHelper::cleanRut('f@a18765432@@7'); // "18.765.432-7"
echo RutHelper::cleanRut('18.290.743-K', false); // "18.290.743-k"
```

#### `separateRut`

Cleans and separates a string into a number and verification digit array.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

RutHelper::separateRut('18.290.743-K', false);

// array(2) [
//    0 => 18290743,
//    1 => 'k',
// ]
```

#### `validate`

Checks if the RUTs issued are valid. If there are many RUTs, it will return `true` if all the RUTs are valid, and `false` if at least one is invalid.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

echo RutHelper::validate('14328145-0'); // true
echo RutHelper::validate('14.328.145-0', '12343580-K'); // true
echo RutHelper::validate(143281450); // true
echo RutHelper::validate('not-a-rut'); // false
echo RutHelper::validate(['14.328.145-0', '12343580-K', 'foo']); // false
```

Alternatively, you can use the `Rut` object itself (which cleans any non-RUT character).

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

// Cleans the rut, and validate it 
echo Rut::make('14328145-0')->isValid(); // true
echo Rut::make('94.328.145-0')->isValid(); // false
echo Rut::make('cleanthis14328145-0-deletethis')->isValid(); // true
```

You can use this to check if the user has responded with a valid RUT, and process the Request if it is.

#### `validateStrict`

You can strictly validate a RUT. The RUT being passed must have the RUT number with thousand separator and hyphen preceding the RUT verification digit.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

// Receive the raw string, and strictly validate it
echo RutHelper::validateStrict('14328145-0'); // false
echo RutHelper::validateStrict('14.328.145-0', '12343580-K'); // false
echo RutHelper::validateStrict(143281450); // false
echo RutHelper::validateStrict('not-a-rut'); // false
echo RutHelper::validateStrict(143281450, 'not-a-rut'); // false
echo RutHelper::validateStrict('14.328.1!45-0'); // false
```

#### `filter`

Filter only the valid RUTs, returning the array of RUTs comprised of only the valid ones.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

// Filter only the valid ruts, and leave the invalid out of the result.
$rutsA = RutHelper::filter('14328145-0', '12343580-K', '94.328.145-0', 'not-a-rut');

var_dump($rutsA);

// array(1) {
//     [0] => 14328145-0
//     [1] => 12343580-K
// } 

$rutsB = RutHelper::filter([
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
#### `rectify`

Receives only the RUT number and returns a valid `Rut` instance with the verification digit. 

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::rectify('18765432');

echo $rut->num; // "18765432"
echo $rut->vd; // "7"
```

> If you pass down a whole RUT, you may get a new RUT with an appended Verification Digit. Ensure you pass down only the RUT number. 

#### `isPerson`

Checks if the RUT is between 0 and 50.000.000, which are usually used for normal people.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

echo RutHelper::isPerson('22605071-k'); // true
```

You can also use the `isPerson()` helper inside a Rut instance.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

echo RutHelper::make('22605071-k')->isPerson(); // true
```

#### `isCompany`

Checks if the RUT is over or equal 50.000.000, which are usually used for companies.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

echo RutHelper::isCompany('50000000-7'); // true
```

You can also use the `isCompany()` helper inside a Rut instance.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

echo RutHelper::make('50000000-7')->isCompany(); // true
```

#### `isEqual`

Receives multiple RUTs and return true if all of them are equal, independently of how these are formatted, **even if these are invalid**.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;
use Application\Models\User;

$ruts = RutHelper::isEqual(
    'thisisARut12343580-K',
    '12343580-K',
    User::getRutFromDatabase()
);

echo $ruts; // true
```

You can also use the `isEqual()` helper inside a Rut instance.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;
use Application\Models\User;

echo Rut::make(User::getRutFromDatabase())->isEqual(
    'thisisARut12343580-K', 
    '12343580-k'
); // true
```

#### `getVd`

Returns the verification digit for a given number.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

$vd = RutHelper::getVd(12343580);

echo $vd; // 'K'
```

#### `unpack`

Takes an array and, if only one item was issued containing an array, this item contents will be returned.

```php
<?php

use DarkGhostHunter\RutUtils\RutHelper;

$unpacked = RutHelper::unpack([
    ['12343580K', '22605071-k', 500000007],
]);

echo count($unpacked); // 3
```

### Make Callbacks

For convenience, you can register callbacks to be executed after you use `many()` and `manyOrThrow()`. For example, you may want to use this to manipulate how the Ruts are handled before these are returned.

Register them using the `after()` static method. The callable receives the array of Ruts as the come, and **must return** the result.

```php
<?php

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutHelper;

Rut::after(function ($ruts) {
    
    $persons = 0;
    
    foreach ($ruts as $rut) {
        $persons += (int) RutHelper::isPerson($rut); 
    }
    
    return array_merge($ruts, [
        'persons' => $persons,
        'companies' => count($ruts) - $persons,
    ]);
});
```

If you register multiple callbacks, these will be executed in the order they were registered.

### Serialization

Sometimes you may want to store your Rut instance somewhere, or serialize it to JSON, or a string. In this package you're covered from all angles.

#### Serialize / Unserialize

By default, a Rut instance is serialized as a raw string, which is latter reconstructed quickly by just dividing the string into number and verification digit:

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::make('22605071-k');

echo serialize($rut); // C:28:"DarkGhostHunter\RutUtils\Rut":9:{106663092}
``` 

This is pretty much heavily optimized for low storage and quick instancing. The rest will depend on your serialization engine.

#### String  

There are multiple ways to set the format to use with a Rut instance when is serialized as a string:

* Strict: Default option. Serializes with thousand separator and hyphen.
* Basic: No thousand separator, only the hyphen.
* Raw: No thousand separator nor hyphen.

These can be set globally using the static methods, and as a per-instance basis using the dynamic calls available in itself.

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

$rut = Rut::make('22605071-k');

Rut::allFormatStrict(); 

echo (string)$rut; // "22.605.071-K"

Rut::allFormatBasic(); 

echo (string)$rut; // "22605071-K"

Rut::allFormatRaw(); 

echo (string)$rut; // "22605071K"

// Per instance
echo $rut->toStrictString(); // "22.605.071-K"
echo $rut->toBasicString(); // "22605071-K"
echo $rut->toRawString(); // "22605071K"
```

#### JSON

By default, when casting to JSON, the result is a string. You can change this to be an array of the number and verification digit using static methods or per-instance cases: 

```php
<?php

use DarkGhostHunter\RutUtils\Rut;

Rut::allJsonAsArray();

$rut = Rut::make('22605071-k');

echo json_encode($rut); // {"num":"22605071","vd":"K"}

Rut::allJsonAsString();

echo json_encode($rut); // "22.605.071-K"

$rut->jsonAsArray();

echo json_encode($rut); // {"num":"22605071","vd":"K"}

$rut->jsonAsString();

echo json_encode($rut); // "22.605.071-K"
```

## Global helper

In version 2.0, all helpers have been killed and now you have only one called `rut()`. It works as a proxy for `Rut::makeOr`, but accepts a default in case of invalid ruts. If no parameter is issued, an instance of the Rut Generator is returned.

```php
<?php

$rut = rut('10.666.309-2');

echo $rut; // '10.666.309-2';

$rut = rut('an invalid rut', 'use this!');

echo $rut; // 'use this!'

$rut = rut()->generate();

echo $rut; // '20.750.456-4'
```

## License

This package is licenced by the [MIT License](LICENSE).