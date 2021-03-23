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
 * Describes an optional value
 *
 * @template T
 * The optional value
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
     * @param Exception $msg
     * @return mixed
     * @psalm-return T
     * @throws Exception the message if the value is a None.
     */
    abstract public function expect(Exception $msg);

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @return mixed
     * @psalm-return T
     * @throws OptionException if the value is a None.
     */
    abstract public function unwrap();

    /**
     * Unwraps a result, yielding the content of a Some. Else, it returns optb.
     *
     * @param mixed $optb
     * @psalm-param T $optb
     * @return mixed
     * @psalm-return T
     */
    abstract public function unwrapOr($optb);

    /**
     * Returns the contained value or computes it from a closure.
     *
     * @param Closure $op
     * @psalm-param Closure(mixed...):T $op
     * @return mixed
     * @psalm-return T
     */
    abstract public function unwrapOrElse(Closure $op);

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
    abstract public function map(Closure $mapper): Option;

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
    abstract public function mapOr($default, Closure $mapper);

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
    abstract public function mapOrElse(Closure $default, Closure $mapper);

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
    abstract public function okOr($err): Result;

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
    abstract public function okOrElse(Closure $err): Result;

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return array
     * @psalm-return array<int, T>
     */
    abstract public function iter(): array;

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
    abstract public function and(Option $optb): Option;

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
    abstract public function andThen(Closure $op): Option;

    /**
     * Returns the option if it contains a value, otherwise returns optb.
     *
     * @param Option $optb
     * @psalm-param Option<T> $optb
     * @return Option
     * @psalm-return Option<T>
     */
    abstract public function or(Option $optb): Option;

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param Closure $op
     * @psalm-param Closure(mixed...):Option<T> $op
     * @return Option
     * @psalm-return Option<T>
     */
    abstract public function orElse(Closure $op): Option;

    /**
     * The attached pass-through args will be unpacked into extra args into chained closures
     *
     * @param mixed ...$args
     * @return Option
     * @psalm-return Option<T>
     */
    abstract public function with(...$args): Option;

    /**
     * Create a Some<T> if T is something using isset(T), None otherwise
     *
     * @template V
     *
     * @param mixed $thing
     * @psalm-param V|null $thing
     * @return Option Option<V>
     * @psalm-return Option<V>
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
     * @template V
     *
     * @param mixed $thing
     * @psalm-param V|null $thing
     * @return Option Option<V>
     * @psalm-return Option<V>
     */
    public static function fromEmptyable($thing): Option
    {
        return !empty($thing) ? new Some($thing) : new None;
    }

    /**
     * Iterates over an iterable and creates a Some<V> from the first item, returning None if the iterable is empty
     *
     * @template V
     *
     * @param iterable $iterable
     * @psalm-param iterable<V> $iterable
     * @return Option
     * @psalm-return Option<V>
     * @throws OptionException
     */
    public static function fromFirst(iterable $iterable): Option
    {
        foreach ($iterable as $item) {
            return new Some($item);
        }

        return new None;
    }
}
