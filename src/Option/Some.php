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
 * @extends Option<T>
 */
final class Some extends Option
{
    /**
     * Some constructor.
     *
     * @param T $value
     */
    public function __construct(
        private $value,
    ) {
    }

    public function isSome(): bool
    {
        return true;
    }

    public function isNone(): bool
    {
        return false;
    }

    /**
     * @return T
     */
    public function expect(Exception $msg)
    {
        return $this->value;
    }

    /**
     * @return T
     */
    public function unwrap()
    {
        return $this->value;
    }

    public function unwrapOr($optb)
    {
        return $this->value;
    }

    public function unwrapOrElse(callable $op)
    {
        return $this->value;
    }

    public function inspect(callable $f): Option
    {
        $f($this->value);

        return $this;
    }

    public function map(callable $mapper): Option
    {
        return new self($mapper($this->value));
    }

    public function mapOr($default, callable $mapper)
    {
        return $mapper($this->value);
    }

    public function mapOrElse(callable $default, callable $mapper)
    {
        return $mapper($this->value);
    }

    public function okOr($err): Result
    {
        return new Ok($this->value);
    }

    public function okOrElse(callable $err): Result
    {
        return new Ok($this->value);
    }

    public function iter(): iterable
    {
        return [$this->value];
    }

    public function and(Option $optb): Option
    {
        return $optb;
    }

    public function andThen(callable $op): Option
    {
        return $op($this->value);
    }

    public function filter(callable $predicate): Option
    {
        if ($predicate($this->value)) {
            return $this;
        }

        return new None();
    }

    public function or(Option $optb): Option
    {
        return $this;
    }

    public function orElse(callable $op): Option
    {
        return $this;
    }
}
