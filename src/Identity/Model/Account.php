<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\Identity\Events\AccountWasActivated;
use OG\Account\Domain\Identity\Events\AccountWasCreated;
use OG\Account\Domain\Identity\Events\PasswordWasReset;
use OG\Core\Domain\AggregateRoot;
use OG\Core\Domain\Entity;
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
     * @var DateTime
     */
    private $soft_locked;

    /**
     * @var bool
     */
    private $hard_locked;

    /**
     * @var bool
     */
    private $enabled;

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
        $this->soft_locked = DateTime::now();
        $this->hard_locked = false;
        $this->enabled = false;
        $this->update();

        $this->recordThat(new AccountWasCreated($id, $alias));
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
     * Reset the password.
     *
     * @param HashedPassword $password
     */
    public function resetPassword(HashedPassword $password)
    {
        if (!$this->isLocked()) {
            $this->password = $password;
            $this->recordThat(new PasswordWasReset($this->getId(), $this->getAlias()));
            $this->update();

            return;
        }

        throw AccountIsLocked::withId($this->getId());
    }

    /**
     * Activate the account.
     */
    public function activate()
    {
        if (!$this->isLocked()) {
            $this->enabled = true;
            $this->recordThat(new AccountWasActivated($this->getId(), $this->getAlias()));
            $this->update();

            return;
        }

        throw AccountIsLocked::withId($this->getId());
    }

    /**
     * Lock the account using a soft lock.
     *
     * @param DateTime $lockUntil
     */
    public function lockSoft(DateTime $lockUntil)
    {
        $this->soft_locked = $lockUntil;
    }

    /**
     * Lock the account using a hard lock.
     */
    public function lockHard()
    {
        $this->hard_locked = true;
    }

    /**
     * Unlock the account.
     */
    public function unlock()
    {
        $this->soft_locked = DateTime::now();
        $this->hard_locked = false;
    }

    /**
     * Enable the account.
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable the account.
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Return the entity identifier.
     *
     * @return AccountId
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
     * Check if the account is locked.
     *
     * @return bool
     */
    public function isLocked()
    {
        return $this->isSoftLocked() || $this->isHardLocked();
    }

    /**
     * Check if the account is soft-locked.
     *
     * @return bool
     */
    public function isSoftLocked()
    {
        return $this->soft_locked > DateTime::now();
    }

    /**
     * Check if the account is hard-locked.
     *
     * @return bool
     */
    public function isHardLocked()
    {
        return $this->hard_locked;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
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
