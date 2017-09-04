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
     * @param mixed $value
     * @return Prewk\Option\Ok
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
     * @param mixed $value
     * @return Prewk\Option\Err
     */
    function none(): Prewk\Option\None {
        return new Prewk\Option\None;
    }
}
