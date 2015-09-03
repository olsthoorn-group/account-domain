<?php

namespace OG\Account\Domain;

interface Identifier
{
    /**
     * Creates an identifier object from a string representation.
     *
     * @param $string
     *
     * @return Identifier
     */
    public static function fromString($string);

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function __toString();

    /**
     * Compares the object to another Identifier object. Returns true if both have the same type and value.
     *
     * @param $other
     *
     * @return bool
     */
    public function equals(Identifier $other);
}
