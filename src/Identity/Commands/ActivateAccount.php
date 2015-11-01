<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Account\Domain\Identity\Model\Email;
use OG\Core\Domain\Command;

/**
 * Command to activate an account.
 */
class ActivateAccount implements Command
{
    /**
     * @var Email
     */
    private $alias;

    /**
     * @var ActivationCode
     */
    private $code;

    /**
     * ActivateAccount constructor.
     *
     * @param Email          $alias
     * @param ActivationCode $code
     */
    public function __construct(Email $alias, ActivationCode $code)
    {
        $this->alias = $alias;
        $this->code = $code;
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
     * Get the activation code.
     *
     * @return ActivationCode
     */
    public function getActivationCode()
    {
        return $this->code;
    }
}
