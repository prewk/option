<?php

declare(strict_types=1);

namespace Prewk\Option\Test\StaticAnalysis;

use Prewk\Option;
use Prewk\Option\None;
use Prewk\Option\Some;

function foo(Option $option): void
{
    if ($option->isSome()) {
        bar($option);
    }

    if ($option->isNone()) {
        baz($option);
    }
}

/**
 * @psalm-suppress UnusedParam
 */
function bar(Some $some): void
{
}

/**
 * @psalm-suppress UnusedParam
 */
function baz(None $none): void
{
}
