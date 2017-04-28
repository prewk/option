# PHP Option Object [![Build Status](https://travis-ci.org/prewk/option.svg)](https://travis-ci.org/prewk/option) [![Coverage Status](https://coveralls.io/repos/github/prewk/option/badge.svg?branch=master)](https://coveralls.io/github/prewk/option?branch=master)

A PHP implementation of [Rust's Option type](https://doc.rust-lang.org/std/option/enum.Option.html) with roughly the same API.

## Installation

```php
composer require prewk/option
```

## Usage

```
use Prewk\Option;
use Prewk\Option\{Some, None};

function findSomething(): Option {
    // ...
    if ($foundSomething) {
        return new Some($thing);
    } else {
        return new None;
    }
}

function findSomethingElse(): Result {
    // ...
    if ($foundSomething) {
        return new Some($thing);
    } else {
        return new None;
    }
}

// Fallback to value
$value = findSomething()->unwrapOr(null);

// Fallback to option and throw an exception if both fail
$value = findSomething()->or(findSomethingElse())->unwrap();

// Throw custom exception on missing thing (None)
$value = findSomething()->expect(new Exception("Oh noes!"));
```

## License

MIT & Apache 2.0
