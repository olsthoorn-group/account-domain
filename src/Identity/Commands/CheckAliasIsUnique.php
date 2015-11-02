<?php

namespace OG\Account\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Model\Email;
use OG\Core\Domain\Command;

/**
 * Command to check if the alias provided is unique.
 */
class CheckAliasIsUnique implements Command
{
    /**
     * @var Email
     */
    private $alias;

    /**
     * CheckAliasIsUnique constructor.
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
