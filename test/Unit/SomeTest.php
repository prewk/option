<?php

declare(strict_types=1);

namespace Prewk\Option\Test\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Prewk\Option\None;
use Prewk\Option\Some;
use Prewk\Result\Ok;

/**
 * @covers \Prewk\Option\Some
 */
final class SomeTest extends TestCase
{
    public function testItIsSome(): void
    {
        self::assertTrue((new Some('foo'))->isSome());
    }

    public function testItIsNotNone(): void
    {
        self::assertFalse((new Some('foo'))->isNone());
    }

    public function testItExpects(): void
    {
        self::assertSame('foo', (new Some('foo'))->expect(new Exception('bar')));
    }

    public function testItUnwraps(): void
    {
        self::assertSame('foo', (new Some('foo'))->unwrap());
    }

    public function testItUnwrapsOr(): void
    {
        /** @var Some<string> */
        $some = new Some('foo');

        self::assertSame('foo', $some->unwrapOr('bar'));
    }

    public function testItUnwrapsOrElse(): void
    {
        /** @var Some<string> */
        $some = new Some('foo');

        self::assertSame('foo', $some->unwrapOrElse(static fn () => 'bar'));
    }

    public function testItInspects(): void
    {
        $some = new Some('foo');
        $callCounter = 0;

        self::assertSame($some, $some->inspect(static function () use (&$callCounter) {
            ++$callCounter;
        }));
        self::assertSame(1, $callCounter);
    }

    public function testItMaps(): void
    {
        self::assertEquals(new Some('foobar'), (new Some('foo'))->map(static fn ($value) => $value . 'bar'));
    }

    public function testItMapsOr(): void
    {
        self::assertSame('foobaz', (new Some('foo'))->mapOr('bar', static fn ($value) => $value . 'baz'));
    }

    public function testItMapsOrElse(): void
    {
        self::assertSame(
            'foobar',
            (new Some('foo'))->mapOrElse(static fn () => 'baz', static fn ($value) => $value . 'bar')
        );
    }

    public function testItReturnsErr(): void
    {
        self::assertEquals(new Ok('foo'), (new Some('foo'))->okOr('bar'));
    }

    public function testItComputesErr(): void
    {
        self::assertEquals(new Ok('foo'), (new Some('foo'))->okOrElse(static fn () => 'bar'));
    }

    public function testItIters(): void
    {
        self::assertSame(['foo'], (new Some('foo'))->iter());
    }

    public function testItAnds(): void
    {
        self::assertEquals(new Some('bar'), (new Some('foo'))->and(new Some('bar')));
    }

    public function testItAndsThen(): void
    {
        self::assertEquals(
            new Some('foobar'),
            (new Some('foo'))->andThen(static fn ($value) => new Some($value . 'bar'))
        );
    }

    public function testItFiltersIn(): void
    {
        self::assertEquals(new Some('foo'), (new Some('foo'))->filter(static fn ($value) => $value === 'foo'));
    }

    /**
     * @uses \Prewk\Option\None
     */
    public function testItFiltersOut(): void
    {
        self::assertEquals(new None(), (new Some('foo'))->filter(static fn () => false));
    }

    public function testItDoesntOr(): void
    {
        /** @var Some<string> */
        $some = new Some('foo');

        self::assertSame($some, $some->or(new Some('bar')));
    }

    public function testItDoesntOrElse(): void
    {
        /** @var Some<string> */
        $some = new Some('foo');

        self::assertSame($some, $some->orElse(static fn () => new Some('bar')));
    }

    public function testItHandlesPass(): void
    {
        /** @var Some<string> */
        $some = new Some('foo', 'bar', 0);

        self::assertEquals(new Some(0), $some->map(static fn (string $value, string $aString, int $anInt) => $anInt));
        self::assertEquals(new Some(1), $some->with(1)->map(static fn (string $value, int $anInt) => $anInt));
    }
}
