<?php

namespace OG\Account\Test\Domain\Identity\Commands;

use OG\Account\Domain\Identity\Commands\RequestReminder;
use OG\Account\Domain\Identity\Model\Email;

class RequestReminderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_command()
    {
        $alias = Email::fromString('local@domain.com');

        $command = new RequestReminder($alias);

        $this->assertInstanceOf(RequestReminder::class, $command);
        $this->assertEquals($alias, $command->getAlias());
    }
}
