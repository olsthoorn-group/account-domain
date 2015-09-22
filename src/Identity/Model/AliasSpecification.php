<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Specification for an alias.
 */
interface AliasSpecification
{
    /**
     * Check to see if the specification is satisfied.
     *
     * @param Email $email
     *
     * @return bool
     */
    public function isSatisfiedBy(Email $email);
}
