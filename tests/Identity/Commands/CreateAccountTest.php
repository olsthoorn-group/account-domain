<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\CreateAccount;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\Password;

class CreateAccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $alias = Email::fromString('local@domain.com');
        $password = new Password('valid_password');

        $command = new CreateAccount($alias, $password);

        $this->assertInstanceOf(CreateAccount::class, $command);
        $this->assertEquals($alias, $command->getAlias());
        $this->assertEquals($password, $command->getPassword());
    }
}
