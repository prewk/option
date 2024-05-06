<?php

declare(strict_types=1);

namespace Prewk\Option\Test\Unit;

use PHPUnit\Framework\TestCase;
use Prewk\Option;
use Prewk\Option\None;
use Prewk\Option\Some;

/**
 * @covers \Prewk\Option
 * @uses \Prewk\Option\None
 * @uses \Prewk\Option\Some
 */
final class OptionTest extends TestCase
{
    public function testItReturnsNoneOnNull(): void
    {
        self::assertEquals(new None(), Option::fromNullable(null));
    }

    public function testItReturnsSomeOnNonNull(): void
    {
        self::assertEquals(new Some('foo'), Option::fromNullable('foo'));
    }

    public function testItReturnsNoneOnUndefinedKey(): void
    {
        self::assertEquals(new None(), Option::fromKey([], 'foo'));
    }

    public function testItReturnsSomeOnDefinedKey(): void
    {
        self::assertEquals(new Some('foo'), Option::fromKey([
            'bar' => 'foo',
        ], 'bar'));
    }

    public function testItReturnsNoneOnEmptyable(): void
    {
        self::assertEquals(new None(), Option::fromEmptyable(''));
    }

    public function testItReturnsSomeOnNonEmpty(): void
    {
        self::assertEquals(new Some('foo'), Option::fromEmptyable('foo'));
    }

    public function testItReturnsNoneOnEmptyIterable(): void
    {
        self::assertEquals(new None(), Option::fromFirst([]));
    }

    public function testItReturnsSomeForFirstValueOfIterable(): void
    {
        self::assertEquals(new Some('foo'), Option::fromFirst(['foo', 'bar']));
    }
}
