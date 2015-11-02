<?php

namespace OG\Account\Domain\Identity\Events;

use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Core\Domain\DomainEvent;
use OG\Core\Domain\Model\DateTime;

/**
 * Event fired when an activation is created.
 */
class ActivationWasCreated implements DomainEvent
{
    /**
     * @var ActivationCode
     */
    public $activationCode;

    /**
     * @var DateTime
     */
    public $happened_at;

    /**
     * ActivationWasCreated constructor.
     *
     * @param ActivationCode $code
     */
    public function __construct(ActivationCode $code)
    {
        $this->activationCode = $code;
        $this->happened_at = DateTime::now();
    }
}
