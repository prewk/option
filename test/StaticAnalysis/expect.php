<?php

declare(strict_types=1);

namespace Prewk\Option\Test\StaticAnalysis;

use Exception;
use Prewk\Option\None;
use Prewk\Option\Some;

/**
 * @throws Exception
 */
function expectNone(): never
{
    (new None())->expect(new Exception('foo'));
}

function expectSome(): string
{
    return (new Some('foo'))->expect(new Exception('bar'));
}
