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
     * @return Account|null Account object or null if it can't be found
     */
    public function findById(AccountId $accountId);

    /**
     * Find an account by its alias.
     *
     * @param Email $alias
     *
     * @return Account|null Account object or null if it can't be found
     */
    public function findByAlias(Email $alias);

    /**
     * Add an account to the collection.
     *
     * @param Account $account
     */
    public function add(Account $account);

    /**
     * Update an account in the collection.
     *
     * @param Account $account
     */
    public function update(Account $account);

    /**
     * Remove an account from the collection.
     *
     * @param AccountId $accountId
     */
    public function delete(AccountId $accountId);

    /**
     * Returns the next identity.
     *
     * @return AccountId
     */
    public function nextIdentity();
}
