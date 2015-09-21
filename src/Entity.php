<?php

namespace OG\Account\Domain;

/**
 * Common methods all entities must have.
 */
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
     * @param Entity $other
     *
     * @return bool
     */
    public function equals(Entity $other);
}
