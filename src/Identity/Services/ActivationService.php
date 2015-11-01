<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Activation;
use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Account\Domain\Identity\Model\ActivationRepository;
use OG\Account\Domain\Identity\Model\Email;

/**
 * Service for requesting activation codes for activating an account.
 */
class ActivationService
{
    /**
     * @var ActivationRepository
     */
    private $activationRepository;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var ActivationCodeHashingService
     */
    private $codeHashingService;

    /**
     * Create a new ActivationService.
     *
     * @param ActivationRepository         $activationRepository
     * @param AccountRepository          $accountRepository
     * @param ActivationCodeHashingService $codeHashingService
     */
    public function __construct(ActivationRepository $activationRepository, AccountRepository $accountRepository, ActivationCodeHashingService $codeHashingService)
    {
        $this->activationRepository = $activationRepository;
        $this->accountRepository = $accountRepository;
        $this->codeHashingService = $codeHashingService;
    }

    /**
     * Request an activation token.
     *
     * @param Email $alias
     *
     * @return Activation
     */
    public function request(Email $alias)
    {
        // Check if an account exists with that alias
        $this->findAccountByAlias($alias);

        // Delete old activations for this alias
        $this->activationRepository->deleteExistingActivationsForAlias($alias);

        // Hash activation code
        $code = ActivationCode::generate();
        $hashedCode = $this->codeHashingService->hash($code);

        // Create new activation
        $id = $this->activationRepository->nextIdentity();
        $activation = Activation::request($id, $alias, $code, $hashedCode);
        $this->activationRepository->add($activation);

        return $activation;
    }

    /**
     * Check to see if the email and token combination are valid.
     *
     * @param Email        $alias
     * @param ActivationCode $code
     *
     * @throws ActivationIsNotFound
     * @throws ActivationCodeIsInvalid
     * @throws ActivationTimedOut
     */
    public function checkToken(Email $alias, ActivationCode $code)
    {
        // Find the activation in the collection
        $activation = $this->findActivationByAlias($alias);

        // Get the activation code
        $hashedCode = $activation->getCode();

        // Verify that the activation code is valid
        if ($this->codeHashingService->verify($code, $hashedCode)) {

            // Verify the activation is valid
            if ($activation->isValid()) {
                return;
            }

            throw ActivationTimedOut::withActivationCode($code);
        }

        throw ActivationCodeIsInvalid::withActivationCode($code);
    }

    /**
     * Activate an account.
     *
     * @param Email        $alias
     * @param ActivationCode $code
     *
     * @return Account
     *
     * @throws ActivationIsNotFound
     * @throws ActivationCodeIsInvalid
     * @throws ActivationTimedOut
     * @throws AliasIsNotFound
     */
    public function activate(Email $alias, ActivationCode $code)
    {
        // Check if the alias and code combination are valid
        $this->checkToken($alias, $code);

        // Find the user belonging to the request
        $account = $this->findAccountByAlias($alias);

        // Activate account
        $account->activate();
        $this->accountRepository->update($account);
        $this->activationRepository->deleteByCode($code);

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
     * Attempt to find a activation by its alias.
     *
     * @param Email $alias
     *
     * @return Activation
     *
     * @throws ActivationIsNotFound
     */
    private function findActivationByAlias(Email $alias)
    {
        $activation = $this->activationRepository->findByAlias($alias);

        if ($activation) {
            return $activation;
        }

        throw ActivationIsNotFound::withAlias($alias);
    }
}
