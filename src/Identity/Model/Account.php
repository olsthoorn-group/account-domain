<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\AggregateRoot;
use OG\Account\Domain\Entity;
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
     * @var \DateTimeImmutable
     */
    private $updated_at;

    /**
     * @var \DateTimeImmutable
     */
    private $created_at;

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
        $this->created_at = new \DateTimeImmutable();
        $this->update();
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

    /**
     * Return when the account was created.
     *
     * @return \DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Return when the account was last updated.
     *
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Method called when a value has been updated.
     */
    private function update()
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    /**
     * Compares the object to another Entity object. Returns true if both have the same identifier.
     *
     * @param $other
     *
     * @return bool
     */
    public function equals(Entity $other)
    {
        return $this->getId() == $other->getId();
    }
}
