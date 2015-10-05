<?php

namespace OG\Account\Domain\Identity\Model;

use DateTimeZone;
use OG\Account\Domain\ValueObject;

/**
 * DateTime value object to represent a date and time object.
 */
class DateTime extends \DateTimeImmutable implements ValueObject
{
    /**
     * @var static
     */
    private static $testDate;

    /**
     * Create new DateTime.
     *
     * @param string            $time     DateTime string format accepted by default DateTime php object
     * @param DateTimeZone|null $timezone DateTimeZone or null for the default timezone set by php
     */
    public function __construct($time = 'now', $timezone = null)
    {
        if (static::$testDate && $time == 'now') {
            parent::__construct(static::$testDate->format('Y-m-d H:i:s'), static::$testDate->getTimezone());
        } else {
            parent::__construct($time, $timezone);
        }
    }

    /**
     * Return the test DateTime instance.
     *
     * @return static
     */
    public static function getTestDateTime()
    {
        return static::$testDate;
    }

    /**
     * Set the test DateTime instance.
     *
     * @param DateTime $dateTime
     */
    public static function setTestDateTime(DateTime $dateTime)
    {
        static::$testDate = $dateTime;
    }

    /**
     * Clear the test DateTime instance.
     */
    public static function clearTestDateTime()
    {
        static::$testDate = null;
    }

    /**
     * Creates a value object from a string representation.
     *
     * @param string $string
     *
     * @return static
     */
    public static function fromString($string)
    {
        $parts = explode('|', $string);

        return new static($parts[0], new DateTimeZone($parts[1]));
    }

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function toString()
    {
        return $this->format('Y-m-d H:i:s').'|'.$this->getTimezone()->getName();
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
