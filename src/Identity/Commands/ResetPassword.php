<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Core\Domain\Command;

/**
 * Command to reset the password of an account.
 */
class ResetPassword implements Command
{
    /**
     * @var Email
     */
    private $alias;

    /**
     * @var ReminderCode
     */
    private $code;

    /**
     * ResetPassword constructor.
     *
     * @param Email        $alias
     * @param ReminderCode $code
     */
    public function __construct(Email $alias, ReminderCode $code)
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
     * Get the reminder code.
     *
     * @return ReminderCode
     */
    public function getReminderCode()
    {
        return $this->code;
    }
}
