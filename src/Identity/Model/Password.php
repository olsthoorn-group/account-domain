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
     * @param string $value
     */
    public function __construct($value)
    {
        \Assert\that($value)
            ->string('The argument has to be a string')
            ->betweenLength(8, 100, 'The password has to be 1 to 255 characters long');

        $this->value = $value;
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
