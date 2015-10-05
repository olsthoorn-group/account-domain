<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Account\Domain\ValueObject;

/**
 * ReminderCode value object to represent a random reminder code in the application domain.
 */
class ReminderCode implements ValueObject
{
    const RANDOM_BYTES_SIZE = 32;

    /**
     * @var string
     */
    private $value;

    /**
     * Create a new ReminderCode.
     *
     * @param string $value
     */
    private function __construct($value)
    {
        // TODO: Add extra tests for this assertion.
        \Assert\That($value)
            ->string('Argument has to be a string')
            ->length(static::RANDOM_BYTES_SIZE * 2, 'ReminderCode has to be '.static::RANDOM_BYTES_SIZE * 2 .' characters long');

        $this->value = $value;
    }

    /**
     * Generate a new ReminderCode.
     *
     * @return ReminderCode
     */
    public static function generate()
    {
        return new static(bin2hex(random_bytes(self::RANDOM_BYTES_SIZE)));
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
