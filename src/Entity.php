<?php

namespace OG\Account\Domain;

interface Entity
{
    /**
     * Return the entity identifier.
     *
     * @return Identifier
     */
    public function getId();

    /**
     * Compares the object to another Entity object. Returns true if both have the same identifier.
     *
     * @param $other
     *
     * @return bool
     */
    public function equals(Entity $other);
}
