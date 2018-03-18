<?php
/**
 * From
 *
 * Helper class for creating Options from things
 * @author Oskar Thornblad
 */

declare(strict_types=1);

namespace Prewk\Option;

use Traversable;
use Prewk\Option;

/**
 * Helper class for creating Options from things
 */
class From
{
    /**
     * Create a Some<T> if T is something using isset(T), None otherwise
     *
     * @param mixed $thing
     * @return Option Option<T>
     */
    public static function nullable($thing): Option
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
    public static function key(array $coll, $key): Option
    {
        return array_key_exists($key, $coll) ? new Some($coll[$key]) : new None;
    }

    /**
     * Create a Some<T> if T is non-empty using empty(T), None otherwise
     *
     * @param mixed $thing
     * @return Option Option<T>
     */
    public static function emptyable($thing): Option
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
    public static function first($iterable): Option
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