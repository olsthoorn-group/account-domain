<?php

namespace OG\Account\Domain;

/**
 * Executes a Command.
 */
interface CommandHandler
{
    /**
     * Executes the given command.
     *
     * @param Command $command
     */
    public function handle(Command $command);
}
