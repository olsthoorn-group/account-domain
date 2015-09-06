<?php

namespace OG\Account\Domain;

use Rhumsaa\Uuid\Uuid;

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
    public function __toString()
    {
        return $this->value->toString();
    }

    /**
     * Compares the object to another Identifier object. Returns true if both have the same type and value.
     *
     * @param $other
     *
     * @return bool
     */
    public function equals(Identifier $other)
    {
        return $this == $other;
    }
}
