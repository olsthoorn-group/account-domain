<?php

namespace OG\Account\Domain;

/**
 * Immutable object for which the equality is based on value.
 */
interface ValueObject
{
    /**
     * Creates a value object from a string representation.
     *
     * @param $string
     *
     * @return ValueObject
     */
    public static function fromString($string);

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function toString();

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function __toString();

    /**
     * Compares the object to another value object. Returns true if both have the same type and value.
     *
     * @param ValueObject $other
     *
     * @return bool
     */
    public function equals(ValueObject $other);
}
