<?php
/**
 * None
 *
 * Documentation and API borrowed from Rust: https://doc.rust-lang.org/std/option/enum.Option.html
 * @author Oskar Thornblad
 */

declare(strict_types=1);

namespace Prewk\Option;

use Closure;
use Exception;
use Prewk\Option;

/**
 * None
 */
class None implements Option
{
    /**
     * None constructor.
     */
    public function __construct() {}

    /**
     * Returns true if the option is a Some value.
     *
     * @return bool
     */
    public function isSome(): bool
    {
        return false;
    }

    /**
     * Returns true if the option is a None value.
     *
     * @return bool
     */
    public function isNone(): bool
    {
        return true;
    }

    /**
     * Unwraps a result, yielding the content of a Some.
     *
     * @throws Exception (the message) if the value is a None.
     * @param Exception $msg
     * @return mixed
     */
    public function expect(Exception $msg)
    {
        throw $msg;
    }

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @throws OptionException if the value is a None.
     * @return mixed
     */
    public function unwrap()
    {
        throw new OptionException("Unwrapped a None");
    }

    /**
     * Unwraps a result, yielding the content of a Some. Else, it returns optb.
     *
     * @param mixed $optb
     * @return mixed
     */
    public function unwrapOr($optb)
    {
        return $optb;
    }

    /**
     * Returns the contained value or computes it from a closure.
     *
     * @param Closure $op
     * @return mixed
     */
    public function unwrapOrElse(Closure $op)
    {
        return $op();
    }

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @param Closure $mapper
     * @return Option
     */
    public function map(Closure $mapper): Option
    {
        return $this;
    }

    /**
     * Applies a function to the contained value (if any), or returns a default (if not).
     *
     * @param mixed $default
     * @param Closure $mapper
     * @return mixed
     */
    public function mapOr($default, Closure $mapper)
    {
        return $default;
    }

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @param Closure $default
     * @param Closure $mapper
     * @return mixed
     */
    public function mapOrElse(Closure $default, Closure $mapper)
    {
        return $default();
    }

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return array
     */
    public function iter(): array
    {
        return [];
    }

    /**
     * Returns None if the option is None, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    public function and(Option $optb): Option
    {
        return $this;
    }

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @param Closure $op
     * @return Option
     */
    public function andThen(Closure $op): Option
    {
        return $this;
    }

    /**
     * Returns the option if it contains a value, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    public function or(Option $optb): Option
    {
        return $optb;
    }

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param Closure $op
     * @return Option
     * @throws OptionException on closure return type mismatch
     */
    public function orElse(Closure $op): Option
    {
        $option = $op();

        if (!($option instanceof Option)) {
            throw new OptionException("Op must return an Option");
        }

        return $option;
    }
}