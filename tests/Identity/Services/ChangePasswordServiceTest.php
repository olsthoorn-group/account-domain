<?php

namespace OG\Account\Test\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\Identity\Services\AccountIdIsNotFound;
use OG\Account\Domain\Identity\Services\ChangePasswordService;
use OG\Account\Domain\Identity\Services\PasswordHashingService;

class ChangePasswordServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AccountRepository|\Mockery\Mock
     */
    private $accountRepository;

    /**
     * @var PasswordHashingService|\Mockery\Mock
     */
    private $passwordHashingService;

    /**
     * @var Account|\Mockery\Mock
     */
    private $account;

    /**
     * @var ChangePasswordService
     */
    private $service;

    public function setUp()
    {
        $this->accountRepository = m::mock(AccountRepository::class);
        $this->passwordHashingService = m::mock(PasswordHashingService::class);
        $this->account = m::mock(Account::class);
        $this->service = new ChangePasswordService($this->accountRepository, $this->passwordHashingService);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_user_can_not_be_found()
    {
        $this->setExpectedException(AccountIdIsNotFound::class);

        $this->accountRepository
            ->shouldReceive('findById')
            ->once()
            ->andReturn(null);

        $this->service->changePassword(AccountId::generate(), new Password('new_valid_password'));
    }

    /**
     * @test
     */
    public function it_should_change_password()
    {
        $this->accountRepository
            ->shouldReceive('findById')
            ->once()
            ->andReturn($this->account);
        $this->passwordHashingService
            ->shouldReceive('hash')
            ->once()
            ->andReturn(new HashedPassword('new_valid_hashed_password'));
        $this->account
            ->shouldReceive('changePassword')
            ->once();
        $this->accountRepository
            ->shouldReceive('update')
            ->once();

        $account = $this->service->changePassword(AccountId::generate(), new Password('new_valid_password'));

        $this->assertInstanceOf(Account::class, $account);
    }

    public function tearDown()
    {
        m::close();
    }
}
