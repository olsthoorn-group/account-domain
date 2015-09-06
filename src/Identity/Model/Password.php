<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Password value object to represent an password in the application domain.
 */
class Password
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
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Compares the object to another Password object. Returns true if both have the same type and value.
     *
     * @param Password $other
     *
     * @return bool
     */
    public function equals(Password $other)
    {
        return $this == $other;
    }
}
