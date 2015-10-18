<?php

namespace OG\Account\Tests\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Services\CreateAccountService;
use OG\Account\Domain\Identity\Services\PasswordHashingService;
use OG\Account\Domain\ValueIsNotUniqueException;

class CreateAccountServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AccountRepository|\Mockery\Mock
     */
    private $repository;

    /**
     * @var PasswordHashingService|\Mockery\Mock
     */
    private $hashing;

    /**
     * @var CreateAccountService
     */
    private $service;

    public function setUp()
    {
        $this->repository = m::mock(AccountRepository::class);
        $this->hashing = m::mock(PasswordHashingService::class);
        $this->service = new CreateAccountService($this->repository, $this->hashing);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_email_is_not_unique()
    {
        $this->setExpectedException(ValueIsNotUniqueException::class);

        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(['id' => 1]);

        $this->service->createAccount('local@domain.com', 'password');
    }

    /**
     * @test
     */
    public function it_should_create_new_account()
    {
        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);
        $this->repository
            ->shouldReceive('nextIdentity')
            ->once()
            ->andReturn(AccountId::generate());
        $this->hashing
            ->shouldReceive('hash')
            ->once()
            ->andReturn(new HashedPassword('password'));
        $this->repository
            ->shouldReceive('add')
            ->once();

        $account = $this->service->createAccount('local@domain.com', 'password');

        $this->assertInstanceOf(Account::class, $account);
    }

    public function tearDown()
    {
        m::close();
    }
}
