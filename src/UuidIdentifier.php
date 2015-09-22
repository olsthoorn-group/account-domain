<?php

namespace OG\Account\Domain;

use Rhumsaa\Uuid\Uuid;

/**
 * Provides the common functionality of an identifier.
 */
abstract class UuidIdentifier implements Identifier, GeneratesIdentifier
{
    /**
     * @var Uuid
     */
    private $value;

    private function __construct(Uuid $value)
    {
        $this->value = $value;
    }

    /**
     * Generate a new Identifier.
     *
     * @return Identifier
     */
    public static function generate()
    {
        return new static(Uuid::uuid4());
    }

    /**
     * Creates an identifier object from a string.
     *
     * @param string $string an uuid
     *
     * @return static
     */
    public static function fromString($string)
    {
        \Assert\That($string)
            ->string('Argument has to be a string')
            ->uuid('String has to be an UUID');

        return new static(Uuid::fromString($string));
    }

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function toString()
    {
        return $this->value->toString();
    }

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Compares the object to another value object. Returns true if both have the same type and value.
     *
     * @param ValueObject $other
     *
     * @return bool
     */
    public function equals(ValueObject $other)
    {
        return $this == $other;
    }
}
