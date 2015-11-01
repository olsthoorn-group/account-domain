<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\Password;
use OG\Core\Domain\Command;

/**
 * Command to create an account.
 */
class CreateAccount implements Command
{
    /**
     * @var Email
     */
    private $alias;

    /**
     * @var Password
     */
    private $password;

    /**
     * CreateAccount constructor.
     *
     * @param Email    $alias
     * @param Password $password
     */
    public function __construct(Email $alias, Password $password)
    {
        $this->alias = $alias;
        $this->password = $password;
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

    /**
     * Get the password.
     *
     * @return Password
     */
    public function getPassword()
    {
        return $this->password;
    }
}
