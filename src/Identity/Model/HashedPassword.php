<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * HashedPassword value object to represent a password that is hashed in the application domain.
 */
class HashedPassword
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new HashedPassword.
     *
     * @param string $value A hashed password
     */
    public function __construct($value)
    {
        \Assert\that($value)
            ->string('Argument has to be a string');

        $this->value = $value;
    }

    /**
     * Creates an hashed password object from a string.
     *
     * @param string $string A hashed password
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
     * Compares the object to another HashedPassword object. Returns true if both have the same type and value.
     *
     * @param HashedPassword $other
     *
     * @return bool
     */
    public function equals(HashedPassword $other)
    {
        return $this == $other;
    }
}
