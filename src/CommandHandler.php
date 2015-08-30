<?php
/**
 * Created by PhpStorm.
 * User: m.olsthoorn
 * Date: 8/30/2015
 * Time: 11:57 PM
 */

namespace OG\Account\Domain;

/**
 * Executes a Command.
 */
interface CommandHandler
{
    /**
     * Executes the given command
     *
     * @param Command $command
     * @return void
     */
    public function handle(Command $command);
}
