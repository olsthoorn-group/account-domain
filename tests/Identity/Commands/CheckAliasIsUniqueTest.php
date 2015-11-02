<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\CheckAliasIsUnique;
use OG\Account\Domain\Identity\Model\Email;

class CheckAliasIsUniqueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $alias = Email::fromString('local@domain.com');

        $command = new CheckAliasIsUnique($alias);

        $this->assertInstanceOf(CheckAliasIsUnique::class, $command);
        $this->assertEquals($alias, $command->getAlias());
    }
}
