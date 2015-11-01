<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\ActivateAccount;
use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Account\Domain\Identity\Model\Email;

class ActivateAccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $alias = Email::fromString('local@domain.com');
        $code = ActivationCode::generate();

        $command = new ActivateAccount($alias, $code);

        $this->assertInstanceOf(ActivateAccount::class, $command);
        $this->assertEquals($alias, $command->getAlias());
        $this->assertEquals($code, $command->getActivationCode());
    }
}
