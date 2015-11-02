<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Core\Domain\ValueObject;

/**
 * ActivationCode value object to represent a random activation code in the application domain.
 */
class ActivationCode implements ValueObject
{
    const RANDOM_BYTES_SIZE = 32;

    /**
     * @var string
     */
    private $value;

    /**
     * Create a new ActivationCode.
     *
     * @param string $value
     */
    private function __construct($value)
    {
        \Assert\that($value)
            ->string('Argument has to be a string')
            ->regex('/^[0-9A-Fa-f]*$/', 'String has to be hexadecimal')
            ->length(static::RANDOM_BYTES_SIZE * 2, 'ActivationCode has to be '.static::RANDOM_BYTES_SIZE * 2 .' characters long');

        $this->value = strtolower($value);
    }

    /**
     * Generate a new ActivationCode.
     *
     * @return ActivationCode
     */
    public static function generate()
    {
        return new static(bin2hex(random_bytes(self::RANDOM_BYTES_SIZE)));
    }

    /**
     * Creates an activation code object from a string representation.
     *
     * @param string $string an activation code
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
