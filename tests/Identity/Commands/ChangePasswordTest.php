<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\ChangePassword;
use OG\Account\Domain\Identity\Model\Password;

class ChangePasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $password = new Password('valid_password');

        $command = new ChangePassword($password);

        $this->assertInstanceOf(ChangePassword::class, $command);
        $this->assertEquals($password, $command->getPassword());
    }
}
