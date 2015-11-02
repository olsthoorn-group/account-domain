<?php

namespace OG\Account\Domain\Identity\Events;

use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\Email;
use OG\Core\Domain\DomainEvent;
use OG\Core\Domain\Model\DateTime;

/**
 * Event fired when an account was activated.
 */
class AccountWasActivated implements DomainEvent
{
    /**
     * @var AccountId
     */
    public $accountId;

    /**
     * @var Email
     */
    public $accountAlias;

    /**
     * @var DateTime
     */
    public $happened_at;

    /**
     * AccountWasActivated constructor.
     *
     * @param AccountId $id
     * @param Email     $alias
     */
    public function __construct(AccountId $id, Email $alias)
    {
        $this->accountId = $id;
        $this->accountAlias = $alias;
        $this->happened_at = DateTime::now();
    }
}
