<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Password;

/**
 * Service to change the password of an account.
 */
class ChangePasswordService
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var PasswordHashingService
     */
    private $passwordHashingService;

    /**
     * Create a new ChangePasswordService.
     *
     * @param AccountRepository      $accountRepository
     * @param PasswordHashingService $passwordHashingService
     */
    public function __construct(AccountRepository $accountRepository, PasswordHashingService $passwordHashingService)
    {
        $this->accountRepository = $accountRepository;
        $this->passwordHashingService = $passwordHashingService;
    }

    /**
     * Change the password of an account.
     *
     * @param AccountId $id
     * @param Password  $password
     *
     * @return Account
     *
     * @throws AccountIdIsNotFound
     */
    public function changePassword(AccountId $id, Password $password)
    {
        // Find the account belonging to the request
        $account = $this->findAccountById($id);

        // Hash new password
        $hashedPassword = $this->passwordHashingService->hash($password);

        // Change password
        $account->changePassword($hashedPassword);
        $this->accountRepository->update($account);

        return $account;
    }

    /**
     * Attempt to find an account by its id.
     *
     * @param AccountId $id
     *
     * @return Account
     *
     * @throws AccountIdIsNotFound
     */
    private function findAccountById(AccountId $id)
    {
        $account = $this->accountRepository->findById($id);

        if ($account) {
            return $account;
        }

        throw AccountIdIsNotFound::withId($id);
    }
}
