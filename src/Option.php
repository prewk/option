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
use Prewk\Option\{OptionException, Some, None};
use Traversable;

/**
 * @template T
 * Describes a Option
 */
abstract class Option
{
    /**
     * Returns true if the option is a Some value.
     *
     * @return bool
     */
    abstract public function isSome(): bool;

    /**
     * Returns true if the option is a None value.
     *
     * @return bool
     */
    abstract public function isNone(): bool;

    /**
     * Unwraps a result, yielding the content of a Some.
     *
     * @throws the message if the value is a None.
     * @param Exception $msg
     * @return mixed
     * @psalm-return T
     */
    abstract public function expect(Exception $msg);

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @throws if the value is a None.
     * @return mixed
     * @psalm-return T
     */
    abstract public function unwrap();

    /**
     * Unwraps a result, yielding the content of a Some. Else, it returns optb.
     *
     * @param mixed $optb
     * @psalm-param T $optb
     * @return mixed
     * @psalm-return T|mixed
     */
    abstract public function unwrapOr($optb);

    /**
     * Returns the contained value or computes it from a closure.
     *
     * @param Closure $op
     * @return mixed
     * @psalm-return T|mixed
     */
    abstract public function unwrapOrElse(Closure $op);

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @param Closure $mapper
     * @return Option
     */
    abstract public function map(Closure $mapper): Option;

    /**
     * Applies a function to the contained value (if any), or returns a default (if not).
     *
     * @param mixed $default
     * @param Closure $mapper
     * @return mixed
     */
    abstract public function mapOr($default, Closure $mapper);

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @param Closure $default
     * @param Closure $mapper
     * @return mixed
     */
    abstract public function mapOrElse(Closure $default, Closure $mapper);

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err).
     *
     * @param mixed $err
     * @return Result<T, mixed>
     */
    abstract public function okOr($err): Result;

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err()).
     *
     * @param Closure $err
     * @return Result<T, mixed>
     */
    abstract public function okOrElse(Closure $err): Result;

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return array
     * @psalm-return array<int, mixed>
     */
    abstract public function iter(): array;

    /**
     * Returns None if the option is None, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    abstract public function and(Option $optb): Option;

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @param Closure $op
     * @return Option
     */
    abstract public function andThen(Closure $op): Option;

    /**
     * Returns the option if it contains a value, otherwise returns optb.
     *
     * @param Option $optb
     * @return Option
     */
    abstract public function or(Option $optb): Option;

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param Closure $op
     * @return Option
     */
    abstract public function orElse(Closure $op): Option;

    /**
     * The attached pass-through args will be unpacked into extra args into chained closures
     *
     * @param array ...$args
     * @return Option
     */
    abstract public function with(...$args): Option;

    /**
     * Create a Some<T> if T is something using isset(T), None otherwise
     *
     * @param mixed $thing
     * @return Option Option<T>
     */
    public static function fromNullable($thing): Option
    {
        return isset($thing) ? new Some($thing) : new None;
    }

    /**
     * Create a Some<V> from C[K] if it exists using array_key_exists(C, K), None otherwise
     *
     * @param array $coll C
     * @param mixed $key
     * @return Option Option<V>
     */
    public static function fromKey(array $coll, $key): Option
    {
        return array_key_exists($key, $coll) ? new Some($coll[$key]) : new None;
    }

    /**
     * Create a Some<T> if T is non-empty using empty(T), None otherwise
     *
     * @param mixed $thing
     * @return Option Option<T>
     */
    public static function fromEmptyable($thing): Option
    {
        return !empty($thing) ? new Some($thing) : new None;
    }

    /**
     * Iterates over T and creates a Some<V> from the first item, returning None if T is empty
     *
     * @param array|Iterable $iterable T<V>
     * @return Option Option<V>
     * @throws OptionException
     */
    public static function fromFirst($iterable): Option
    {
        if (!is_array($iterable) && !($iterable instanceof Traversable)) {
            throw new OptionException("Couldn't create Option from first item in non-iterable");
        }

        foreach ($iterable as $item) {
            return new Some($item);
        }

        return new None;
    }
}