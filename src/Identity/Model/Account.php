<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\AggregateRoot;
use OG\Account\Domain\Identifier;
use OG\Account\Domain\RecordsEvents;

/**
 * Account created by an user in the application domain.
 */
class Account implements AggregateRoot
{
    use RecordsEvents;

    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var Email
     */
    private $alias;

    /**
     * @var HashedPassword
     */
    private $password;

    /**
     * Create a new account.
     *
     * @param AccountId      $accountId
     * @param Email          $email
     * @param HashedPassword $hashedPassword
     */
    private function __construct(AccountId $accountId, Email $email, HashedPassword $hashedPassword)
    {
        $this->id = $accountId;
        $this->alias = $email;
        $this->password = $hashedPassword;
    }

    /**
     * Create a new account.
     *
     * @param AccountId      $accountId
     * @param Email          $email
     * @param HashedPassword $hashedPassword
     *
     * @return Account
     */
    public static function create(AccountId $accountId, Email $email, HashedPassword $hashedPassword)
    {
        return new self($accountId, $email, $hashedPassword);
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
     * Return the alias of the account.
     *
     * @return Email
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Return the password of the account.
     *
     * @return HashedPassword
     */
    public function getPassword()
    {
        return $this->password;
    }
}
