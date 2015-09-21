<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Collection oriented repository for accounts.
 */
interface AccountRepository
{
    /**
     * Find an account by its identity.
     *
     * @param AccountId $accountId
     *
     * @return Account
     */
    public function findById(AccountId $accountId);

    /**
     * Find an account by its alias.
     *
     * @param Email $alias
     *
     * @return Account
     */
    public function findByAlias(Email $alias);

    /**
     * Add an account to the collection.
     *
     * @param Account $account
     */
    public function add(Account $account);

    /**
     * Remove an account from the collection.
     *
     * @param AccountId $accountId
     */
    public function remove(AccountId $accountId);

    /**
     * Returns the next identity.
     *
     * @return AccountId
     */
    public function nextIdentity();
}
