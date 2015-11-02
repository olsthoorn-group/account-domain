<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\RequestActivation;
use OG\Account\Domain\Identity\Model\Email;

class RequestActivationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $alias = Email::fromString('local@domain.com');

        $command = new RequestActivation($alias);

        $this->assertInstanceOf(RequestActivation::class, $command);
        $this->assertEquals($alias, $command->getAlias());
    }
}
