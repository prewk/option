<?php

declare(strict_types=1);

namespace Prewk\Option\Test\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Prewk\Option\None;
use Prewk\Option\OptionException;
use Prewk\Option\Some;
use Prewk\Result\Err;

/**
 * @covers \Prewk\Option\None
 */
final class NoneTest extends TestCase
{
    public function testItIsNotSome(): void
    {
        self::assertFalse((new None())->isSome());
    }

    public function testItIsNone(): void
    {
        self::assertTrue((new None())->isNone());
    }

    public function testItThrowsOnExpect(): void
    {
        $exception = new Exception('foo');
        $this->expectException($exception::class);
        $this->expectExceptionMessage('foo');

        (new None())->expect($exception);
    }

    public function testItThrowsOnUnwrap(): void
    {
        $this->expectException(OptionException::class);
        $this->expectExceptionMessage('Unwrapped a None');

        (new None())->unwrap();
    }

    public function testItUnwrapsOr(): void
    {
        $optb = 'foo';

        self::assertSame($optb, (new None())->unwrapOr($optb));
    }

    public function testItUnwrapsOrElse(): void
    {
        $value = 'foo';

        self::assertSame($value, (new None())->unwrapOrElse(static fn () => $value));
    }

    public function testItDoesntInspect(): void
    {
        $none = new None();
        $callCounter = 0;

        self::assertSame($none, $none->inspect(static function () use (&$callCounter) {
            ++$callCounter;
        }));
        self::assertSame(0, $callCounter);
    }

    public function testItDoesntMap(): void
    {
        self::assertEquals(new None(), (new None())->map(static fn () => 'foo'));
    }

    public function testItReturnsDefaultOnMapOr(): void
    {
        $default = 'foo';

        self::assertSame($default, (new None())->mapOr($default, static fn () => 'bar'));
    }

    public function testItComputesDefaultOnMapOrElse(): void
    {
        self::assertSame('foo', (new None())->mapOrElse(static fn () => 'foo', static fn () => 'bar'));
    }

    public function testItReturnsErr(): void
    {
        self::assertEquals(new Err('foo'), (new None())->okOr('foo'));
    }

    public function testItComputesErr(): void
    {
        self::assertEquals(new Err('foo'), (new None())->okOrElse(static fn () => 'foo'));
    }

    public function testItDoesntIter(): void
    {
        self::assertSame([], (new None())->iter());
    }

    /**
     * @uses \Prewk\Option\Some
     */
    public function testItDoesntAnd(): void
    {
        self::assertEquals(new None(), (new None())->and(new Some('foo')));
    }

    public function testItDoesntAndThen(): void
    {
        self::assertEquals(new None(), (new None())->andThen(static fn () => new Some('foo')));
    }

    public function testItDoesntFilter(): void
    {
        $none = new None();

        self::assertSame($none, $none->filter(static fn () => true));
    }

    /**
     * @uses \Prewk\Option\Some
     */
    public function testItOr(): void
    {
        $optb = new Some('foo');

        self::assertSame($optb, (new None())->or($optb));
    }

    /**
     * @uses \Prewk\Option\Some
     */
    public function testItOrElse(): void
    {
        $optb = new Some('foo');

        self::assertSame($optb, (new None())->orElse(static fn () => $optb));
    }

    /**
     * @uses \Prewk\Option\Some
     */
    public function testItHandlesPass(): void
    {
        /** @var None<string> */
        $none = new None('bar');

        self::assertEquals(new Some('bar'), $none->orElse(static fn (string $aString) => new Some($aString)));
        self::assertEquals(
            new Some('baz'),
            $none->with('baz')->orElse(static fn (string $aString) => new Some($aString))
        );
    }
}
