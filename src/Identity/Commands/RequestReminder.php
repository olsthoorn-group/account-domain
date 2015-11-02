<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\Email;
use OG\Core\Domain\Command;

/**
 * Command to request a reminder to reset a password.
 */
class RequestReminder implements Command
{
    /**
     * @var Email
     */
    private $alias;

    /**
     * RequestReminder constructor.
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
