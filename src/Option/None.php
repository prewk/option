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
use Prewk\Result;
use Prewk\Result\Err;

/**
 * No value
 *
 * @template T
 * The optional value
 *
 * @inherits Option<T>
 */
class None extends Option
{
    /**
     * @var array
     */
    private $pass;

    /**
     * None constructor
     *
     * @param array ...$pass
     */
    public function __construct(...$pass) {
        $this->pass = $pass;
    }

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
     * @param Exception $msg
     * @return void
     * @psalm-return never-return
     * @throws Exception the message if the value is a None.
     */
    public function expect(Exception $msg)
    {
        throw $msg;
    }

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @return void
     * @psalm-return never-return
     * @throws OptionException if the value is a None.
     */
    public function unwrap()
    {
        throw new OptionException("Unwrapped a None");
    }

    /**
     * Unwraps a result, yielding the content of a Some. Else, it returns optb.
     *
     * @param mixed $optb
     * @psalm-param T $optb
     * @return mixed
     * @psalm-return T
     */
    public function unwrapOr($optb)
    {
        return $optb;
    }

    /**
     * Returns the contained value or computes it from a closure.
     *
     * @param Closure $op
     * @psalm-param Closure(mixed...):T $op
     * @return mixed
     * @psalm-return T
     */
    public function unwrapOrElse(Closure $op)
    {
        return $op(...$this->pass);
    }

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @template U
     *
     * @param Closure $mapper
     * @psalm-param Closure(T=,mixed...):U $mapper
     * @return Option
     * @psalm-return Option<U>
     */
    public function map(Closure $mapper): Option
    {
        return $this;
    }

    /**
     * Applies a function to the contained value (if any), or returns a default (if not).
     *
     * @template U
     *
     * @param mixed $default
     * @psalm-param U $default
     * @param Closure $mapper
     * @psalm-param Closure(T=,mixed...):U $mapper
     * @return mixed
     * @psalm-return U
     */
    public function mapOr($default, Closure $mapper)
    {
        return $default;
    }

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template U
     *
     * @param Closure $default
     * @psalm-param Closure(mixed...):U $default
     * @param Closure $mapper
     * @psalm-param Closure(T=,mixed...):U $mapper
     * @return mixed
     * @psalm-return U
     */
    public function mapOrElse(Closure $default, Closure $mapper)
    {
        return $default(...$this->pass);
    }

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return array
     * @psalm-return array<int, T>
     */
    public function iter(): array
    {
        return [];
    }

    /**
     * Returns None if the option is None, otherwise returns optb.
     *
     * @template U
     *
     * @param Option $optb
     * @psalm-param Option<U> $optb
     * @return Option
     * @psalm-return Option<U>
     */
    public function and(Option $optb): Option
    {
        return $this;
    }

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @template U
     *
     * @param Closure $op
     * @psalm-param Closure(T=,mixed...):Option<U> $op
     * @return Option
     * @psalm-return Option<U>
     */
    public function andThen(Closure $op): Option
    {
        return $this;
    }

    /**
     * Returns the option if it contains a value, otherwise returns optb.
     *
     * @param Option $optb
     * @psalm-param Option<T> $optb
     * @return Option
     * @psalm-return Option<T>
     */
    public function or(Option $optb): Option
    {
        return $optb;
    }

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param Closure $op
     * @psalm-param Closure(mixed...):Option<T> $op
     * @return Option
     * @psalm-return Option<T>
     *
     * @throws OptionException on closure return type mismatch
     * @psalm-assert !Closure():Option $op
     *
     * @psalm-suppress DocblockTypeContradiction We cannot be completely sure, that in argument valid callable
     */
    public function orElse(Closure $op): Option
    {
        $option = $op(...$this->pass);

        if (!($option instanceof Option)) {
            throw new OptionException("Op must return an Option");
        }

        return $option;
    }

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err).
     *
     * @template E
     *
     * @param mixed $err
     * @psalm-param E $err
     * @return Result
     * @psalm-return Result<T, E>
     */
    public function okOr($err): Result
    {
        return new Err($err, ...$this->pass);
    }

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err()).
     *
     * @template E
     *
     * @param Closure $err
     * @psalm-param Closure(mixed...):E $err
     * @return Result
     * @psalm-return Result<T, E>
     */
    public function okOrElse(Closure $err): Result
    {
        return new Err($err(...$this->pass), ...$this->pass);
    }

    /**
     * The attached pass-through args will be unpacked into extra args into chained closures
     *
     * @param mixed ...$args
     * @return Option
     * @psalm-return Option<T>
     */
    public function with(...$args): Option
    {
        $this->pass = $args;

        return $this;
    }
}
