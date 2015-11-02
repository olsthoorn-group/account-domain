<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;

/**
 * Service for deleting an account.
 */
class DeleteAccountService
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * Create a new DeleteAccountService.
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Delete an account.
     *
     * @param AccountId $id
     */
    public function deleteAccount(AccountId $id)
    {
        $this->accountRepository->delete($id);
    }
}
