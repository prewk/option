<?php
/**
 * Procedural style construction of Option instances
 *
 * @author Oskar Thornblad
 */

if (!function_exists("some")) {
    /**
     * Represent the existance of a value
     *
     * @codeCoverageIgnore
     *
     * @template T
     *
     * @param mixed $value
     * @psalm-param T $value
     * @return Prewk\Option\Some
     * @psalm-return Prewk\Option\Some<T>
     */
    function some($value): Prewk\Option\Some {
        return new Prewk\Option\Some($value);
    }
}

if (!function_exists("none")) {
    /**
     * Represent a lack of value
     *
     * @codeCoverageIgnore
     * @return Prewk\Option\None
     */
    function none(): Prewk\Option\None {
        return new Prewk\Option\None;
    }
}
