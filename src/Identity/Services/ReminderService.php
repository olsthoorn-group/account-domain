<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\Identity\Model\Reminder;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Account\Domain\Identity\Model\ReminderRepository;
use OG\Account\Domain\InvalidValueException;
use OG\Account\Domain\ValueNotFoundException;

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
    private $hashingService;

    /**
     * Create a new ReminderService.
     *
     * @param ReminderRepository     $reminderRepository
     * @param AccountRepository      $accountRepository
     * @param PasswordHashingService $hashingService
     */
    public function __construct(ReminderRepository $reminderRepository, AccountRepository $accountRepository, PasswordHashingService $hashingService)
    {
        $this->reminderRepository = $reminderRepository;
        $this->accountRepository = $accountRepository;
        $this->hashingService = $hashingService;
    }

    /**
     * Request a password reminder Token.
     *
     * @param string $alias
     *
     * @return Reminder
     */
    public function request($alias)
    {
        $alias = Email::fromString($alias);

        // Check if an account exists with that alias
        $this->findAccountByAlias($alias);

        // Delete old reminders for this alias
        $this->reminderRepository->deleteExistingRemindersForAlias($alias);

        // TODO: store only a hashed version if the reminder code.

        // Create new reminder
        $id = $this->reminderRepository->nextIdentity();
        $reminder = Reminder::request($id, $alias, ReminderCode::generate());
        $this->reminderRepository->add($reminder);

        return $reminder;
    }

    /**
     * Check to see if the email and token combination are valid.
     *
     * @param Email        $email
     * @param ReminderCode $code
     *
     * @return bool
     */
    public function check(Email $email, ReminderCode $code)
    {
        // TODO: make the check timing attack resistant.
        $reminder = $this->reminderRepository->findByAliasAndCode($email, $code);

        if ($reminder && $reminder->isValid()) {
            return true;
        }

        return false;
    }

    /**
     * Reset an account password.
     *
     * @param string $email
     * @param string $password
     * @param string $code
     *
     * @return Account
     *
     * @throws InvalidValueException
     * @throws ValueNotFoundException
     */
    public function reset($email, $password, $code)
    {
        $email = Email::fromString($email);
        $code = ReminderCode::fromString($code);

        // Check if the alias and code combination are valid
        if ($this->check($email, $code)) {

            // Find user belonging to the request
            $account = $this->findAccountByAlias($email);

            // Hash new password
            $password = new Password($password);
            $hashedPassword = $this->hashingService->hash($password);

            // Reset password
            $account->resetPassword($hashedPassword);
            $this->accountRepository->update($account);
            $this->reminderRepository->deleteByCode($code);

            return $account;
        }

        throw new InvalidValueException($code->toString().' is not a valid reminder code');
    }

    /**
     * Attempt to find an account by its alias.
     *
     * @param Email $email
     *
     * @return Account
     *
     * @throws ValueNotFoundException
     */
    private function findAccountByAlias(Email $email)
    {
        $account = $this->accountRepository->findByAlias($email);

        if ($account) {
            return $account;
        }

        throw new ValueNotFoundException($email->toString().' is not a used alias');
    }
}
