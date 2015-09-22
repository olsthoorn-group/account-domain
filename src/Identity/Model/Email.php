<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\ValueObject;

/**
 * Email value object to represent an email address in the application domain.
 */
class Email implements ValueObject
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new Email.
     *
     * @param string $value An email with a max length of 100
     */
    public function __construct($value)
    {
        \Assert\that($value)
            ->string('Argument has to be a string')
            ->email('String has to be an email')
            ->maxLength(100, 'Email cannot be longer than 100 characters');

        $this->value = $value;
    }

    /**
     * Creates an email object from a string.
     *
     * @param string $string An email with a max length of 100
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
