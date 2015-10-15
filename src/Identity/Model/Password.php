<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Core\Domain\ValueObject;

/**
 * Password value object to represent a password in the application domain.
 */
class Password implements ValueObject
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new Password.
     *
     * @param string $value A password with a length between 8 to 100 characters
     */
    public function __construct($value)
    {
        \Assert\that($value)
            ->string('Argument has to be a string')
            ->betweenLength(8, 100, 'Password has to be 8 to 100 characters long');

        $this->value = $value;
    }

    /**
     * Creates an password object from a string.
     *
     * @param string $string A password with a length between 8 to 100 characters
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
