<?php
/**
 * Option
 *
 * Documentation and API borrowed from Rust: https://doc.rust-lang.org/std/option/enum.Option.html
 * @author Oskar Thornblad
 */

declare(strict_types=1);

namespace Prewk;

use Closure;
use Exception;

/**
 * Describes a Option
 */
interface Option
{
    /**
     * Returns true if the option is a Some value.
     *
     * @return bool
     */
    public function isSome(): bool;

    /**
     * Returns true if the option is a None value.
     *
     * @return bool
     */
    public function isNone(): bool;

    /**
     * Unwraps a result, yielding the content of a Some.
     *
     * @throws the message if the value is a None.
     * @param Exception $msg
     * @return mixed
     */
    public function expect(Exception $msg);

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @throws if the value is a None.
     * @return mixed
     */
    public function unwrap();

    /**
     * Unwraps a result, yielding the content of a Some. Else, it returns optb.
     *
     * @param mixed $optb
     * @return mixed
     */
    public function unwrapOr($optb);

    /**
     * Returns the contained value or computes it from a closure.
     *
     * @param Closure $op
     * @return mixed
     */
    public function unwrapOrElse(Closure $op);

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @param Closure $mapper
     * @return Option
     */
    public function map(Closure $mapper): Option;

    /**
     * Applies a function to the contained value (if any), or returns a default (if not).
     *
     * @param mixed $default
     * @param Closure $mapper
     * @return mixed
     */
    public function mapOr($default, Closure $mapper);

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @param Closure $default
     * @param Closure $mapper
     * @return mixed
     */
    public function mapOrElse(Closure $default, Closure $mapper);

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err).
     *
     * @param mixed $err
     * @return mixed
     */
    public function okOr($err): Result;

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err()).
     *
     * @param Closure $err
     * @return Result
     */
    public function okOrElse(Closure $err): Result;

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return array
     */
    public function iter(): array;

    /**
     * Returns None if the option is None, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    public function and(Option $optb): Option;

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @param Closure $op
     * @return Option
     */
    public function andThen(Closure $op): Option;

    /**
     * Returns the option if it contains a value, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    public function or(Option $optb): Option;

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param Closure $op
     * @return Option
     */
    public function orElse(Closure $op): Option;
}