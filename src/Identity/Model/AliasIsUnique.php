<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Checks if the alias is unique.
 */
class AliasIsUnique implements AliasSpecification
{
    /**
     * @var AccountRepository
     */
    private $repository;

    /**
     * Create a new instance of the AliasIsUnique specification.
     *
     * @param AccountRepository $repository
     */
    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check to see if the specification is satisfied.
     *
     * @param Email $email
     *
     * @return bool
     */
    public function isSatisfiedBy(Email $email)
    {
        if ($this->repository->findByAlias($email)) {
            return false;
        }

        return true;
    }
}
