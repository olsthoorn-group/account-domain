<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Collection oriented repository for reminders.
 */
interface ReminderRepository
{
    /**
     * Find a Reminder by Alias.
     *
     * @param Email $alias
     *
     * @return Reminder|null Reminder object or null if it can't be found
     */
    public function findByAlias(Email $alias);

    /**
     * Add a new Reminder to the collection.
     *
     * @param Reminder $reminder
     */
    public function add(Reminder $reminder);

    /**
     * Update a Reminder in the collection.
     *
     * @param Reminder $reminder
     */
    public function update(Reminder $reminder);

    /**
     * Delete a Reminder by its ReminderCode.
     *
     * @param ReminderCode $code
     */
    public function deleteByCode(ReminderCode $code);

    /**
     * Delete existing Reminders for Alias.
     *
     * @param Email $email
     */
    public function deleteExistingRemindersForAlias(Email $email);

    /**
     * Delete all expired Reminders.
     */
    public function deleteExpiredReminders();

    /**
     * Return the next identity.
     *
     * @return ReminderId
     */
    public function nextIdentity();
}
