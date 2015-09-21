<?php

namespace OG\Account\Domain;

/**
 * Indicates that an identifier can generate a value by its self.
 */
interface GeneratesIdentifier
{
    /**
     * Generate a new Identifier.
     *
     * @return Identifier
     */
    public static function generate();
}
