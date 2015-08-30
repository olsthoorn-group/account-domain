<?php

namespace OG\Account\Domain;

/**
 * Delivers a Command to the right CommandHandler
 */
interface CommandBus
{
    /**
     * Executes the given command
     *
     * @param Command $command
     * @return void
     */
    public function handle(Command $command);
}
