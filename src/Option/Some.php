<?php
/**
 * Some
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
     */
    private $pass;

    /**
     * Some constructor.
     *
     * @param mixed $value
     * @psalm-param T $value
     * @param array ...$pass
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
     * @throws the message if the value is a None.
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
     * @throws if the value is a None.
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
     * @return mixed
     * @psalm-return T
     */
    public function unwrapOr($optb)
    {
        return $this->value;
    }

    /**
     * Returns the contained value or computes it from a closure.
     *
     * @param Closure $op
     * @return mixed
     * @psalm-return T
     */
    public function unwrapOrElse(Closure $op)
    {
        return $this->value;
    }

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @param Closure $mapper
     * @return Option
     */
    public function map(Closure $mapper): Option
    {
        return new self($mapper($this->value, ...$this->pass));
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
        return $mapper($this->value, ...$this->pass);
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
        return $mapper($this->value, ...$this->pass);
    }

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return array
     * @psalm-return array<int, mixed>
     */
    public function iter(): array
    {
        return [$this->value];
    }

    /**
     * Returns None if the option is None, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    public function and(Option $optb): Option
    {
        return $optb;
    }

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @param Closure $op
     * @return Option
     * @throws OptionException on closure return type mismatch
     */
    public function andThen(Closure $op): Option
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
     * @return Option
     */
    public function or(Option $optb): Option
    {
        return $this;
    }

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param Closure $op
     * @return Option
     */
    public function orElse(Closure $op): Option
    {
        return $this;
    }

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err).
     *
     * @param mixed $err
     * @return Result
     */
    public function okOr($err): Result
    {
        return new Ok($this->value, ...$this->pass);
    }

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err()).
     *
     * @param Closure $err
     * @return Result
     */
    public function okOrElse(Closure $err): Result
    {
        return new Ok($this->value, ...$this->pass);
    }

    /**
     * The attached pass-through args will be unpacked into extra args into chained closures
     *
     * @param array ...$args
     * @return Option
     */
    public function with(...$args): Option
    {
        $this->pass = $args;

        return $this;
    }
}