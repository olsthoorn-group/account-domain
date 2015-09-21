<?php

namespace OG\Account\Domain;

/**
 * Common methods all aggregate roots must have.
 */
interface AggregateRoot extends Entity, HasEvents
{
}
