<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\Identity\Events\ActivationWasCreated;
use OG\Core\Domain\AggregateRoot;
use OG\Core\Domain\Entity;
use OG\Core\Domain\Identifier;
use OG\Core\Domain\Model\DateTime;
use OG\Core\Domain\RecordsEvents;

/**
 * Activation to activate an account.
 */
class Activation implements AggregateRoot
{
    use RecordsEvents;

    /**
     * @var ActivationId
     */
    private $id;

    /**
     * @var Email
     */
    private $alias;

    /**
     * @var HashedActivationCode
     */
    private $code;

    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * Create a new Activation.
     *
     * @param ActivationId         $id
     * @param Email              $alias
     * @param ActivationCode       $code
     * @param HashedActivationCode $hashedCode
     */
    private function __construct(ActivationId $id, Email $alias, ActivationCode $code, HashedActivationCode $hashedCode)
    {
        $this->id = $id;
        $this->alias = $alias;
        $this->code = $hashedCode;
        $this->created_at = DateTime::now();

        $this->recordThat(new ActivationWasCreated($code));
    }

    /**
     * Create a new Activation.
     *
     * @param ActivationId         $id
     * @param Email              $alias
     * @param ActivationCode       $code
     * @param HashedActivationCode $hashedCode
     *
     * @return Activation
     */
    public static function request(ActivationId $id, Email $alias, ActivationCode $code, HashedActivationCode $hashedCode)
    {
        return new self($id, $alias, $code, $hashedCode);
    }

    /**
     * Check to see if the Activation is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->getCreatedAt()->add(new \DateInterval('PT1H')) > DateTime::now();
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
     * Return the account alias.
     *
     * @return Email
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Return the activation code.
     *
     * @return HashedActivationCode
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Return when the activation was created.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
