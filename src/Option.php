<?php

/**
 * Option
 *
 * Documentation and API borrowed from Rust: https://doc.rust-lang.org/std/option/enum.Option.html
 * @author Oskar Thornblad
 */

declare(strict_types=1);

namespace Prewk;

use Exception;
use Prewk\Option\{None, OptionException, Some};

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
     * @psalm-assert-if-true Some<T> $this
     * @psalm-assert-if-false None<T> $this
     */
    abstract public function isSome(): bool;

    /**
     * Returns true if the option is a None value.
     *
     * @psalm-assert-if-true None<T> $this
     * @psalm-assert-if-false Some<T> $this
     */
    abstract public function isNone(): bool;

    /**
     * Unwraps a result, yielding the content of a Some.
     *
     * @return T
     *
     * @throws Exception the message if the value is a None.
     */
    abstract public function expect(Exception $msg);

    /**
     * Unwraps an option, yielding the content of a Some.
     *
     * @return T
     * @throws OptionException if the value is a None.
     */
    abstract public function unwrap();

    /**
     * Unwraps a result, yielding the content of a Some. Else, it returns optb.
     *
     * @param T $optb
     * @return T
     */
    abstract public function unwrapOr($optb);

    /**
     * Returns the contained value or computes it from a callable.
     *
     * @param callable(mixed...): T $op
     * @return T
     */
    abstract public function unwrapOrElse(callable $op);

    /**
     * Calls a function with a reference to the contained value if Some.
     *
     * @param callable(T,mixed...):void $f
     * @return Option<T>
     */
    abstract public function inspect(callable $f): self;

    /**
     * Maps an Option by applying a function to a contained Some value, leaving a None value untouched.
     *
     * @template U
     *
     * @param callable(T=,mixed...):U $mapper
     * @return Option<U>
     */
    abstract public function map(callable $mapper): self;

    /**
     * Applies a function to the contained value (if any), or returns a default (if not).
     *
     * @template U
     *
     * @param U $default
     * @param callable(T=,mixed...):U $mapper
     * @return U
     */
    abstract public function mapOr($default, callable $mapper);

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template U
     *
     * @param callable(mixed...):U $default
     * @param callable(T=,mixed...):U $mapper
     * @return U
     */
    abstract public function mapOrElse(callable $default, callable $mapper);

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err).
     *
     * @template E
     *
     * @param E $err
     * @return Result<T, E>
     */
    abstract public function okOr($err): Result;

    /**
     * Transforms the Option<T> into a Result<T, E>, mapping Some(v) to Ok(v) and None to Err(err()).
     *
     * @template E
     *
     * @param callable(mixed...):E $err
     * @return Result<T, E>
     */
    abstract public function okOrElse(callable $err): Result;

    /**
     * Returns an iterator over the possibly contained value.
     * The iterator yields one value if the result is Some, otherwise none.
     *
     * @return iterable<int, T>
     */
    abstract public function iter(): iterable;

    /**
     * Returns None if the option is None, otherwise returns optb.
     *
     * @template U
     *
     * @param Option<U> $optb
     * @return Option<U>
     */
    abstract public function and(self $optb): self;

    /**
     * Returns None if the option is None, otherwise calls op with the wrapped value and returns the result.
     * Some languages call this operation flatmap.
     *
     * @template U
     *
     * @param callable(T=,mixed...):Option<U> $op
     * @return Option<U>
     */
    abstract public function andThen(callable $op): self;

    /**
     * Returns None if the option is None, otherwise calls predicate with the wrapped value and returns:
     * - Some(t) if predicate returns true (where t is the wrapped value), and
     * - None if predicate returns false.
     *
     * @param callable(T,mixed...):bool $predicate
     * @return Option<T>
     */
    abstract public function filter(callable $predicate): self;

    /**
     * Returns the option if it contains a value, otherwise returns optb.
     *
     * @param Option<T> $optb
     * @return Option<T>
     */
    abstract public function or(self $optb): self;

    /**
     * Returns the option if it contains a value, otherwise calls op and returns the result.
     *
     * @param callable(mixed...):Option<T> $op
     * @return Option<T>
     */
    abstract public function orElse(callable $op): self;

    /**
     * The attached pass-through args will be unpacked into extra args into chained callables
     *
     * @return Option<T>
     */
    abstract public function with(mixed ...$args): self;

    /**
     * Create a Some<T> if T is something using isset(T), None otherwise
     *
     * @template V
     *
     * @param V $thing
     * @return Option<V>
     */
    public static function fromNullable($thing): self
    {
        return isset($thing) ? new Some($thing) : new None();
    }

    /**
     * Create a Some<V> from C[K] if it exists using array_key_exists(C, K), None otherwise
     *
     * @template K of array-key
     * @template V
     *
     * @param array<K, V> $coll C
     * @param K $key
     * @return Option<V>
     */
    public static function fromKey(array $coll, $key): self
    {
        return array_key_exists($key, $coll) ? new Some($coll[$key]) : new None();
    }

    /**
     * Create a Some<T> if T is non-empty using empty(T), None otherwise
     *
     * @template V
     *
     * @param V $thing
     * @return Option<V>
     */
    public static function fromEmptyable($thing): self
    {
        return ! empty($thing) ? new Some($thing) : new None();
    }

    /**
     * Iterates over an iterable and creates a Some<V> from the first item, returning None if the iterable is empty
     *
     * @template V
     *
     * @param iterable<V> $iterable
     * @return Option<V>
     */
    public static function fromFirst(iterable $iterable): self
    {
        foreach ($iterable as $item) {
            return new Some($item);
        }

        return new None();
    }
}
