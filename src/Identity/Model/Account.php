<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\Identity\Events\PasswordWasReset;
use OG\Core\Domain\AggregateRoot;
use OG\Core\Domain\Entity;
use OG\Core\Domain\Identifier;
use OG\Core\Domain\Model\DateTime;
use OG\Core\Domain\RecordsEvents;

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
     * @var DateTime
     */
    private $updated_at;

    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * Create a new account.
     *
     * @param AccountId      $id
     * @param Email          $alias
     * @param HashedPassword $password
     */
    private function __construct(AccountId $id, Email $alias, HashedPassword $password)
    {
        $this->id = $id;
        $this->alias = $alias;
        $this->password = $password;
        $this->created_at = DateTime::now();
        $this->update();
    }

    /**
     * Create a new account.
     *
     * @param AccountId      $id
     * @param Email          $alias
     * @param HashedPassword $password
     *
     * @return Account
     */
    public static function create(AccountId $id, Email $alias, HashedPassword $password)
    {
        return new self($id, $alias, $password);
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
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Return when the account was last updated.
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Reset the password.
     *
     * @param HashedPassword $password
     */
    public function resetPassword(HashedPassword $password)
    {
        $this->password = $password;
        $this->recordThat(new PasswordWasReset());
        $this->update();
    }

    /**
     * Method called when a value has been updated.
     */
    private function update()
    {
        $this->updated_at = DateTime::now();
    }

    /**
     * Compares the object to another Entity object. Returns true if both have the same identifier.
     *
     * @param Entity $other
     *
     * @return bool
     */
    public function equals(Entity $other)
    {
        return $this->getId() == $other->getId();
    }
}
