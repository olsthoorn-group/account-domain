<?php

namespace OG\Account\Test\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\HashedReminderCode;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\Identity\Model\Reminder;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Account\Domain\Identity\Model\ReminderId;
use OG\Account\Domain\Identity\Model\ReminderRepository;
use OG\Account\Domain\Identity\Services\AliasIsNotFound;
use OG\Account\Domain\Identity\Services\PasswordHashingService;
use OG\Account\Domain\Identity\Services\ReminderCodeHashingService;
use OG\Account\Domain\Identity\Services\ReminderCodeIsInvalid;
use OG\Account\Domain\Identity\Services\ReminderIsNotFound;
use OG\Account\Domain\Identity\Services\ReminderService;
use OG\Account\Domain\Identity\Services\ReminderTimedOut;

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
    private $passwordHashingService;

    /**
     * @var ReminderCodeHashingService|\Mockery\Mock
     */
    private $codeHashingService;

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
        $this->passwordHashingService = m::mock(PasswordHashingService::class);
        $this->codeHashingService = m::mock(ReminderCodeHashingService::class);
        $this->reminderService = new ReminderService($this->reminderRepository, $this->accountRepository, $this->passwordHashingService, $this->codeHashingService);
    }
    /**
     * @test
     */
    public function it_should_throw_exception_when_user_does_not_exist()
    {
        $this->setExpectedException(AliasIsNotFound::class);

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
        $this->codeHashingService
            ->shouldReceive('hash')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
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
    public function it_should_find_reminder_and_return_when_valid()
    {
        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->reminder);
        $this->reminder
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->reminder
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->reminderService->checkToken(Email::fromString('local@domain.com'), ReminderCode::generate());
    }

    /**
     * @test
     */
    public function it_should_find_reminder_and_throw_exception_when_reminder_is_not_found()
    {
        $this->setExpectedException(ReminderIsNotFound::class);

        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);

        $this->reminderService->checkToken(Email::fromString('local@domain.com'), ReminderCode::generate());
    }

    /**
     * @test
     */
    public function it_should_find_reminder_and_throw_exception_when_reminder_code_is_invalid()
    {
        $this->setExpectedException(ReminderCodeIsInvalid::class);

        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->reminder);
        $this->reminder
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(false);

        $this->reminderService->checkToken(Email::fromString('local@domain.com'), ReminderCode::generate());
    }

    /**
     * @test
     */
    public function it_should_find_reminder_and_throw_exception_when_reminder_timed_out()
    {
        $this->setExpectedException(ReminderTimedOut::class);

        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->reminder);
        $this->reminder
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->reminder
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(false);

        $this->reminderService->checkToken(Email::fromString('local@domain.com'), ReminderCode::generate());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_during_reset_attempt_when_alias_or_code_are_invalid()
    {
        $this->setExpectedException(ReminderCodeIsInvalid::class);

        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->reminder);
        $this->reminder
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(false);

        $this->reminderService->reset(
            Email::fromString('local@domain.com'),
            new Password('password'),
            ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865')
        );
    }

    /**
     * @test
     */
    public function it_should_throw_exception_during_reset_attempt_when_alias_is_not_found()
    {
        $this->setExpectedException(AliasIsNotFound::class);

        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->reminder);
        $this->reminder
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->reminder
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true);
        $this->accountRepository
            ->shouldReceive('findByAlias')
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
        $this->reminderRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->reminder);
        $this->reminder
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedReminderCode('valid_reminder_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->reminder
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true);
        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->account);
        $this->passwordHashingService
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
