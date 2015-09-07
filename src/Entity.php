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
}
