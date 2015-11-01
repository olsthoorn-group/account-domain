<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Core\Domain\ValueObject;

/**
 * HashedReminderCode value object to represent a random reminder code that is hashed in the application domain.
 */
class HashedReminderCode implements ValueObject
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new HashedReminderCode.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        \Assert\that($value)
            ->string('Argument has to be a string');

        $this->value = $value;
    }

    /**
     * Creates a reminder code object from a string representation.
     *
     * @param string $string a reminder code
     *
     * @return static
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function toString()
    {
        return $this->value;
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
