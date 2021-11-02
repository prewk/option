<?php

/**
 * Some
 *
 * Documentation and API borrowed from Rust: https://doc.rust-lang.org/std/option/enum.Option.html
 * @author Oskar Thornblad
 */

declare(strict_types=1);

namespace Prewk\Option;

use Exception;
use Prewk\Option;
use Prewk\Result;
use Prewk\Result\Ok;

/**
 * Some value
 *
 * @template T
 * The optional value
 *
 * @inherits Option<T>
 */
class Some extends Option
{
    /**
     * @var mixed
     * @psalm-var T
     */
    private $value;

    /**
     * @var array
     * @psalm-var list<mixed>
     */
    private $pass;

    /**
     * Some constructor.
     *
     * @param mixed $value
     * @psalm-param T $value
     * @param mixed ...$pass
     */
    public function __construct($value, ...$pass)
    {
        $this->value = $value;
        $this->pass = $pass;
    }

    /**
     * Returns true if the option is a Some value.
     *
     * @return bool
     */
    public function isSome(): bool
    {
        return true;
    }

    /**
     * Returns true if the option is a None value.
     *
     * @return bool
     */
    public function isNone(): bool
    {
        return false;
    }

    /**
     * Unwraps a result, yielding the content of a Some.
     *
     * @param Exception $msg
     * @return mixed
     * @psalm-return T
     */
    public function expect(Exception $msg)
    {
        return $this->value;
    }

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @return mixed
     * @psalm-return T
     */
    public function unwrap()
    {
        return $this->value;
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
        return $this->value;
    }

    /**
     * Returns the contained value or computes it from a callable.
     *
     * @param callable $op
     * @psalm-param callable(mixed...):T $op
     * @return mixed
     * @psalm-return T
     */
    public function unwrapOrElse(callable $op)
    {
        return $this->value;
    }

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @template U
     *
     * @param callable $mapper
     * @psalm-param callable(T=,mixed...):U $mapper
     * @return Option
     * @psalm-return Option<U>
     */
    public function map(callable $mapper): Option
    {
        return new self($mapper($this->value, ...$this->pass));
    }

    /**
     * Applies a function to the contained value (if any), or returns a default (if not).
     *
     * @template U
     *
     * @param mixed $default
     * @psalm-param U $default
     * @param callable $mapper
     * @psalm-param callable(T=,mixed...):U $mapper
     * @return mixed
     * @psalm-return U
     */
    public function mapOr($default, callable $mapper)
    {
        return $mapper($this->value, ...$this->pass);
    }

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template U
     *
     * @param callable $default
     * @psalm-param callable(mixed...):U $default
     * @param callable $mapper
     * @psalm-param callable(T=,mixed...):U $mapper
     * @return mixed
     * @psalm-return U
     */
    public function mapOrElse(callable $default, callable $mapper)
    {
        return $mapper($this->value, ...$this->pass);
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
        return [$this->value];
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
        return $optb;
    }

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @template U
     *
     * @param callable $op
     * @psalm-param callable(T=,mixed...):Option<U> $op
     * @return Option
     * @psalm-return Option<U>
     *
     * @throws OptionException on callable return type mismatch
     * @psalm-assert !callable(T=):Option $op
     *
     * @psalm-suppress DocblockTypeContradiction We cannot be completely sure, that in argument valid callable
     */
    public function andThen(callable $op): Option
    {
        $result = $op($this->value, ...$this->pass);

        if (!($result instanceof Option)) {
            throw new OptionException("Op must return an Option");
        }

        return $result;
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
        return $this;
    }

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param callable $op
     * @psalm-param callable(mixed...):Option<T> $op
     * @return Option
     * @psalm-return Option<T>
     */
    public function orElse(callable $op): Option
    {
        return $this;
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
        return new Ok($this->value, ...$this->pass);
    }

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err()).
     *
     * @template E
     *
     * @param callable $err
     * @psalm-param callable(mixed...):E $err
     * @return Result
     * @psalm-return Result<T, E>
     */
    public function okOrElse(callable $err): Result
    {
        return new Ok($this->value, ...$this->pass);
    }

    /**
     * The attached pass-through args will be unpacked into extra args into chained callables
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
