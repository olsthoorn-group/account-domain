<?php

namespace OG\Account\Tests\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\Password;
use OG\Account\Domain\Identity\Services\AliasIsAlreadyInUse;
use OG\Account\Domain\Identity\Services\CreateAccountService;
use OG\Account\Domain\Identity\Services\PasswordHashingService;

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
    public function it_should_find_alias_and_it_should_throw_exception_if_email_is_in_use()
    {
        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(['id' => 1]);

        $alias = new Email('local@domain.com');

        $this->assertFalse($this->service->checkAliasIsUnique($alias));
    }

    /**
     * @test
     */
    public function it_should_find_reminder_and_return_false_when_invalid()
    {
        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);

        $alias = new Email('local@domain.com');

        $this->assertTrue($this->service->checkAliasIsUnique($alias));
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_email_is_not_unique()
    {
        $this->setExpectedException(AliasIsAlreadyInUse::class);

        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(['id' => 1]);

        $alias = new Email('local@domain.com');
        $password = new Password('password');

        $this->service->createAccount($alias, $password);
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

        $alias = new Email('local@domain.com');
        $password = new Password('password');

        $account = $this->service->createAccount($alias, $password);

        $this->assertInstanceOf(Account::class, $account);
    }

    public function tearDown()
    {
        m::close();
    }
}
