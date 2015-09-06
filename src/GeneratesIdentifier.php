<?php

namespace OG\Account\Domain;

interface GeneratesIdentifier
{
    /**
     * Generate a new Identifier.
     *
     * @return Identifier
     */
    public static function generate();
}
