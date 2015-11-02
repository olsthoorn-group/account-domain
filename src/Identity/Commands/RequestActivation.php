<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\Email;
use OG\Core\Domain\Command;

/**
 * Command to request an activation of an account.
 */
class RequestActivation implements Command
{
    /**
     * @var Email
     */
    private $alias;

    /**
     * RequestActivation constructor.
     *
     * @param Email $alias
     */
    public function __construct(Email $alias)
    {
        $this->alias = $alias;
    }

    /**
     * Get the alias.
     *
     * @return Email
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
