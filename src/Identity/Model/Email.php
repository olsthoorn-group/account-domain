<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Email value object to represent an email address in the application domain.
 */
class Email
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new Email.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        \Assert\that($value)
            ->email()
            ->maxLength(100, 'Email cannot be longer than 100 characters');

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
     * Compares the object to another Email object. Returns true if both have the same type and value.
     *
     * @param Email $other
     *
     * @return bool
     */
    public function equals(Email $other)
    {
        return $this == $other;
    }
}
