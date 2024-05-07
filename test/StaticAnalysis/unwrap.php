<?php

declare(strict_types=1);

namespace Prewk\Option\Test\StaticAnalysis;

use Prewk\Option\None;
use Prewk\Option\Some;

function unwrapSome(): string
{
    return (new Some('foo'))->unwrap();
}

function unwrapNone(): never
{
    (new None())->unwrap();
}
