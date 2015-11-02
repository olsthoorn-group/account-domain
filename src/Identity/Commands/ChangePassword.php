<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\Password;
use OG\Core\Domain\Command;

/**
 * Command that changes the password on an account.
 */
class ChangePassword implements Command
{
    /**
     * @var Password
     */
    private $password;

    /**
     * ChangePassword constructor.
     *
     * @param Password $password
     */
    public function __construct(Password $password)
    {
        $this->password = $password;
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
