<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\Identity\Model\Reminder;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Account\Domain\Identity\Model\ReminderRepository;

/**
 * Service for requesting reminder codes for resetting an account password.
 */
class ReminderService
{
    /**
     * @var ReminderRepository
     */
    private $reminderRepository;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var PasswordHashingService
     */
    private $passwordHashingService;

    /**
     * @var ReminderCodeHashingService
     */
    private $codeHashingService;

    /**
     * Create a new ReminderService.
     *
     * @param ReminderRepository         $reminderRepository
     * @param AccountRepository          $accountRepository
     * @param PasswordHashingService     $passwordHashingService
     * @param ReminderCodeHashingService $codeHashingService
     */
    public function __construct(ReminderRepository $reminderRepository, AccountRepository $accountRepository, PasswordHashingService $passwordHashingService, ReminderCodeHashingService $codeHashingService)
    {
        $this->reminderRepository = $reminderRepository;
        $this->accountRepository = $accountRepository;
        $this->passwordHashingService = $passwordHashingService;
        $this->codeHashingService = $codeHashingService;
    }

    /**
     * Request a password reminder Token.
     *
     * @param Email $alias
     *
     * @return Reminder
     */
    public function request(Email $alias)
    {
        // Check if an account exists with that alias
        $this->findAccountByAlias($alias);

        // Delete old reminders for this alias
        $this->reminderRepository->deleteExistingRemindersForAlias($alias);

        // Hash reminder code
        $code = ReminderCode::generate();
        $hashedCode = $this->codeHashingService->hash($code);

        // Create new reminder
        $id = $this->reminderRepository->nextIdentity();
        $reminder = Reminder::request($id, $alias, $code, $hashedCode);
        $this->reminderRepository->add($reminder);

        return $reminder;
    }

    /**
     * Check to see if the email and token combination are valid.
     *
     * @param Email        $alias
     * @param ReminderCode $code
     *
     * @throws ReminderIsNotFound
     * @throws ReminderCodeIsInvalid
     * @throws ReminderTimedOut
     */
    public function checkToken(Email $alias, ReminderCode $code)
    {
        // Find the reminder in the collection
        $reminder = $this->findReminderByAlias($alias);

        // Get the reminder code
        $hashedCode = $reminder->getCode();

        // Verify that the reminder code is valid
        if ($this->codeHashingService->verify($code, $hashedCode)) {

            // Verify the reminder is valid
            if ($reminder->isValid()) {
                return;
            }

            throw ReminderTimedOut::withReminderCode($code);
        }

        throw ReminderCodeIsInvalid::withReminderCode($code);
    }

    /**
     * Reset an account password.
     *
     * @param Email        $alias
     * @param Password     $password
     * @param ReminderCode $code
     *
     * @return Account
     *
     * @throws ReminderIsNotFound
     * @throws ReminderCodeIsInvalid
     * @throws ReminderTimedOut
     * @throws AliasIsNotFound
     */
    public function reset(Email $alias, Password $password, ReminderCode $code)
    {
        // Check if the alias and code combination are valid
        $this->checkToken($alias, $code);

        // Find the user belonging to the request
        $account = $this->findAccountByAlias($alias);

        // Hash new password
        $hashedPassword = $this->passwordHashingService->hash($password);

        // Reset password
        $account->resetPassword($hashedPassword);
        $this->accountRepository->update($account);
        $this->reminderRepository->deleteByCode($code);

        return $account;
    }

    /**
     * Attempt to find an account by its alias.
     *
     * @param Email $alias
     *
     * @return Account
     *
     * @throws AliasIsNotFound
     */
    private function findAccountByAlias(Email $alias)
    {
        $account = $this->accountRepository->findByAlias($alias);

        if ($account) {
            return $account;
        }

        throw AliasIsNotFound::withAlias($alias);
    }

    /**
     * Attempt to find a reminder by its alias.
     *
     * @param Email $alias
     *
     * @return Reminder
     *
     * @throws ReminderIsNotFound
     */
    private function findReminderByAlias(Email $alias)
    {
        $reminder = $this->reminderRepository->findByAlias($alias);

        if ($reminder) {
            return $reminder;
        }

        throw ReminderIsNotFound::withAlias($alias);
    }
}
