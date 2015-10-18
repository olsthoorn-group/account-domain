<?php

namespace OG\Account\Test\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\Identity\Model\Reminder;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Account\Domain\Identity\Model\ReminderId;
use OG\Account\Domain\Identity\Model\ReminderRepository;
use OG\Account\Domain\Identity\Services\PasswordHashingService;
use OG\Account\Domain\Identity\Services\ReminderService;
use OG\Account\Domain\InvalidValueException;
use OG\Account\Domain\ValueNotFoundException;

class ReminderServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var Reminder|\Mockery\Mock
     */
    private $reminder;

    /**
     * @var ReminderRepository|\Mockery\Mock
     */
    private $reminderRepository;

    /**
     * @var AccountRepository|\Mockery\Mock
     */
    private $accountRepository;

    /**
     * @var PasswordHashingService|\Mockery\Mock
     */
    private $hashingService;

    /**
     * @var ReminderService
     */
    private $reminderService;

    public function setUp()
    {
        $id = AccountId::generate();
        $email = new Email('local@domain.com');
        $password = new HashedPassword('password');
        $this->account = Account::create($id, $email, $password);

        $this->reminder = m::mock(Reminder::class)->makePartial();
        $this->reminderRepository = m::mock(ReminderRepository::class);
        $this->accountRepository = m::mock(AccountRepository::class);
        $this->hashingService = m::mock(PasswordHashingService::class);
        $this->reminderService = new ReminderService($this->reminderRepository, $this->accountRepository, $this->hashingService);
    }
    /**
     * @test
     */
    public function it_should_throw_exception_when_user_does_not_exist()
    {
        $this->setExpectedException(ValueNotFoundException::class);

        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);

        $this->reminderService->request(Email::fromString('local@domain.com'));
    }

    /**
     * @test
     */
    public function it_should_request_and_return_new_reminder()
    {
        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->account);
        $this->reminderRepository
            ->shouldReceive('deleteExistingRemindersForAlias')
            ->once();
        $this->reminderRepository
            ->shouldReceive('nextIdentity')
            ->once()
            ->andReturn(ReminderId::generate());
        $this->reminderRepository
            ->shouldReceive('add')
            ->once();

        $reminder = $this->reminderService->request(Email::fromString('local@domain.com'));

        $this->assertInstanceOf(Reminder::class, $reminder);
    }

    /**
     * @test
     */
    public function it_should_find_reminder_and_return_true_when_valid()
    {
        $this->reminder
            ->shouldReceive('getCreatedAt')
            ->once()
            ->andReturn(new \DateTimeImmutable());
        $this->reminderRepository
            ->shouldReceive('findByAliasAndCode')
            ->once()
            ->andReturn($this->reminder);

        $this->assertTrue($this->reminderService->check(Email::fromString('local@domain.com'), ReminderCode::generate()));
    }

    /**
     * @test
     */
    public function it_should_find_reminder_and_return_false_when_invalid()
    {
        $this->reminder
            ->shouldReceive('getCreatedAt')
            ->once()
            ->andReturn(new \DateTimeImmutable('yesterday'));
        $this->reminderRepository
            ->shouldReceive('findByAliasAndCode')
            ->once()
            ->andReturn($this->reminder);

        $this->assertFalse($this->reminderService->check(Email::fromString('local@domain.com'), ReminderCode::generate()));
    }

    /**
     * @test
     */
    public function it_should_throw_exception_during_reset_attempt_when_email_or_code_are_invalid()
    {
        $this->setExpectedException(InvalidValueException::class);

        $this->reminderRepository
            ->shouldReceive('findByAliasAndCode')
            ->once()
            ->andReturn(null);

        $this->reminderService->reset(
            Email::fromString('local@domain.com'),
            new Password('password'),
            ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865')
        );
    }

    /**
     * @test
     */
    public function it_should_reset_password_and_return_user()
    {
        $this->reminder
            ->shouldReceive('getCreatedAt')
            ->once()
            ->andReturn(new \DateTimeImmutable());
        $this->reminderRepository
            ->shouldReceive('findByAliasAndCode')
            ->once()
            ->andReturn($this->reminder);
        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->account);
        $this->hashingService
            ->shouldReceive('hash')
            ->once()
            ->andReturn(new HashedPassword('password'));
        $this->accountRepository
            ->shouldReceive('update')
            ->once();
        $this->reminderRepository
            ->shouldReceive('deleteByCode')
            ->once();

        $account = $this->reminderService->reset(
            Email::fromString('local@domain.com'),
            new Password('password'),
            ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865')
        );

        $this->assertInstanceOf(Account::class, $account);
    }

    public function tearDown()
    {
        m::close();
    }
}
