<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\AggregateRoot;
use OG\Account\Domain\Entity;
use OG\Account\Domain\Identifier;
use OG\Account\Domain\Identity\Events\ReminderWasCreated;
use OG\Account\Domain\RecordsEvents;

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
    private $email;

    /**
     * @var ReminderCode
     */
    private $code;

    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * Create a new Reminder.
     *
     * @param ReminderId   $id
     * @param Email        $email
     * @param ReminderCode $code
     */
    private function __construct(ReminderId $id, Email $email, ReminderCode $code)
    {
        $this->id = $id;
        $this->email = $email;
        $this->code = $code;
        $this->created_at = DateTime::now();

        $this->recordThat(new ReminderWasCreated());
    }

    /**
     * Create a new Reminder.
     *
     * @param ReminderId   $id
     * @param Email        $email
     * @param ReminderCode $code
     *
     * @return Reminder
     */
    public static function request(ReminderId $id, Email $email, ReminderCode $code)
    {
        return new self($id, $email, $code);
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
     * Return the reminder email.
     *
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Return the reminder code.
     *
     * @return ReminderCode
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Return when the reminder was created.
     *
     * @return \DateTimeImmutable
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
