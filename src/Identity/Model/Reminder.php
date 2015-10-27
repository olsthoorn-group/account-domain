<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\Identity\Events\ReminderWasCreated;
use OG\Core\Domain\AggregateRoot;
use OG\Core\Domain\Entity;
use OG\Core\Domain\Identifier;
use OG\Core\Domain\Model\DateTime;
use OG\Core\Domain\RecordsEvents;

/**
 * Reminder to reset account password.
 */
class Reminder implements AggregateRoot
{
    use RecordsEvents;

    /**
     * @var ReminderId
     */
    private $id;

    /**
     * @var Email
     */
    private $alias;

    /**
     * @var HashedReminderCode
     */
    private $code;

    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * Create a new Reminder.
     *
     * @param ReminderId         $id
     * @param Email              $alias
     * @param ReminderCode       $code
     * @param HashedReminderCode $hashedCode
     */
    private function __construct(ReminderId $id, Email $alias, ReminderCode $code, HashedReminderCode $hashedCode)
    {
        $this->id = $id;
        $this->alias = $alias;
        $this->code = $hashedCode;
        $this->created_at = DateTime::now();

        $this->recordThat(new ReminderWasCreated($code));
    }

    /**
     * Create a new Reminder.
     *
     * @param ReminderId         $id
     * @param Email              $alias
     * @param ReminderCode       $code
     * @param HashedReminderCode $hashedCode
     *
     * @return Reminder
     */
    public static function request(ReminderId $id, Email $alias, ReminderCode $code, HashedReminderCode $hashedCode)
    {
        return new self($id, $alias, $code, $hashedCode);
    }

    /**
     * Check to see if the Reminder is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->getCreatedAt()->add(new \DateInterval('PT1H')) > DateTime::now();
    }

    /**
     * Return the entity identifier.
     *
     * @return Identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the account alias.
     *
     * @return Email
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Return the reminder code.
     *
     * @return HashedReminderCode
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Return when the reminder was created.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Compares the object to another Entity object. Returns true if both have the same identifier.
     *
     * @param Entity $other
     *
     * @return bool
     */
    public function equals(Entity $other)
    {
        return $this->getId() == $other->getId();
    }
}
