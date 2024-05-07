<?php

/**
 * None
 *
 * Documentation and API borrowed from Rust: https://doc.rust-lang.org/std/option/enum.Option.html
 * @author Oskar Thornblad
 */

declare(strict_types=1);

namespace Prewk\Option;

use Exception;
use Prewk\Option;
use Prewk\Result;
use Prewk\Result\Err;

/**
 * No value
 *
 * @template T
 * The optional value
 *
 * @extends Option<T>
 */
class None extends Option
{
    public function isSome(): bool
    {
        return false;
    }

    public function isNone(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    public function expect(Exception $msg): never
    {
        throw $msg;
    }

    /**
     * @throws OptionException
     */
    public function unwrap(): never
    {
        throw new OptionException('Unwrapped a None');
    }

    public function unwrapOr($optb)
    {
        return $optb;
    }

    public function unwrapOrElse(callable $op)
    {
        return $op();
    }

    public function inspect(callable $f): Option
    {
        return $this;
    }

    public function map(callable $mapper): Option
    {
        return new self();
    }

    public function mapOr($default, callable $mapper)
    {
        return $default;
    }

    public function mapOrElse(callable $default, callable $mapper)
    {
        return $default();
    }

    public function okOr($err): Result
    {
        return new Err($err);
    }

    public function okOrElse(callable $err): Result
    {
        return new Err($err());
    }

    public function iter(): iterable
    {
        return [];
    }

    public function and(Option $optb): Option
    {
        return new self();
    }

    public function andThen(callable $op): Option
    {
        return new self();
    }

    public function filter(callable $predicate): Option
    {
        return $this;
    }

    public function or(Option $optb): Option
    {
        return $optb;
    }

    public function orElse(callable $op): Option
    {
        return $op();
    }
}
