<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\ResetPassword;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\ReminderCode;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $alias = Email::fromString('local@domain.com');
        $code = ReminderCode::generate();

        $command = new ResetPassword($alias, $code);

        $this->assertInstanceOf(ResetPassword::class, $command);
        $this->assertEquals($alias, $command->getAlias());
        $this->assertEquals($code, $command->getReminderCode());
    }
}
