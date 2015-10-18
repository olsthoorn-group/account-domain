<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\AliasIsUnique;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\ValueIsNotUniqueException;

/**
 * Class CreateAccountService.
 */
class CreateAccountService
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var PasswordHashingService
     */
    private $hashingService;

    /**
     * Create a new CreateAccountService.
     *
     * @param AccountRepository      $accountRepository
     * @param PasswordHashingService $hashingService
     */
    public function __construct(AccountRepository $accountRepository, PasswordHashingService $hashingService)
    {
        $this->accountRepository = $accountRepository;
        $this->hashingService = $hashingService;
    }

    /**
     * @param $alias
     * @param $password
     *
     * @return Account
     */
    public function createAccount($alias, $password)
    {
        $alias = new Email($alias);
        $password = new Password($password);

        $this->checkAliasIsUnique($alias);

        $id = $this->accountRepository->nextIdentity();
        $hashedPassword = $this->hashingService->hash($password);

        $account = Account::create($id, $alias, $hashedPassword);
        $this->accountRepository->add($account);

        return $account;
    }

    /**
     * Check that the alias is unique.
     *
     * @param Email $alias
     *
     * @throws ValueIsNotUniqueException
     */
    private function checkAliasIsUnique(Email $alias)
    {
        $specification = new AliasIsUnique($this->accountRepository);

        if (!$specification->isSatisfiedBy($alias)) {
            throw new ValueIsNotUniqueException("$alias is already used");
        }
    }
}
