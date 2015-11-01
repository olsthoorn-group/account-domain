<?php

namespace OG\Account\Test\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\HashedActivationCode;
use OG\Account\Domain\Identity\Model\Activation;
use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Account\Domain\Identity\Model\ActivationId;
use OG\Account\Domain\Identity\Model\ActivationRepository;
use OG\Account\Domain\Identity\Services\AliasIsNotFound;
use OG\Account\Domain\Identity\Services\ActivationCodeHashingService;
use OG\Account\Domain\Identity\Services\ActivationCodeIsInvalid;
use OG\Account\Domain\Identity\Services\ActivationIsNotFound;
use OG\Account\Domain\Identity\Services\ActivationService;
use OG\Account\Domain\Identity\Services\ActivationTimedOut;

class ActivationServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var Activation|\Mockery\Mock
     */
    private $activation;

    /**
     * @var ActivationRepository|\Mockery\Mock
     */
    private $activationRepository;

    /**
     * @var AccountRepository|\Mockery\Mock
     */
    private $accountRepository;

    /**
     * @var ActivationCodeHashingService|\Mockery\Mock
     */
    private $codeHashingService;

    /**
     * @var ActivationService
     */
    private $activationService;

    public function setUp()
    {
        $id = AccountId::generate();
        $email = new Email('local@domain.com');
        $password = new HashedPassword('password');
        $this->account = Account::create($id, $email, $password);

        $this->activation = m::mock(Activation::class)->makePartial();
        $this->activationRepository = m::mock(ActivationRepository::class);
        $this->accountRepository = m::mock(AccountRepository::class);
        $this->codeHashingService = m::mock(ActivationCodeHashingService::class);
        $this->activationService = new ActivationService($this->activationRepository, $this->accountRepository, $this->codeHashingService);
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

        $this->activationService->request(Email::fromString('local@domain.com'));
    }

    /**
     * @test
     */
    public function it_should_request_and_return_new_activation()
    {
        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->account);
        $this->activationRepository
            ->shouldReceive('deleteExistingActivationsForAlias')
            ->once();
        $this->codeHashingService
            ->shouldReceive('hash')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->activationRepository
            ->shouldReceive('nextIdentity')
            ->once()
            ->andReturn(ActivationId::generate());
        $this->activationRepository
            ->shouldReceive('add')
            ->once();

        $activation = $this->activationService->request(Email::fromString('local@domain.com'));

        $this->assertInstanceOf(Activation::class, $activation);
    }

    /**
     * @test
     */
    public function it_should_find_activation_and_return_when_valid()
    {
        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->activation);
        $this->activation
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->activation
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->activationService->checkToken(Email::fromString('local@domain.com'), ActivationCode::generate());
    }

    /**
     * @test
     */
    public function it_should_find_activation_and_throw_exception_when_activation_is_not_found()
    {
        $this->setExpectedException(ActivationIsNotFound::class);

        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);

        $this->activationService->checkToken(Email::fromString('local@domain.com'), ActivationCode::generate());
    }

    /**
     * @test
     */
    public function it_should_find_activation_and_throw_exception_when_activation_code_is_invalid()
    {
        $this->setExpectedException(ActivationCodeIsInvalid::class);

        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->activation);
        $this->activation
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(false);

        $this->activationService->checkToken(Email::fromString('local@domain.com'), ActivationCode::generate());
    }

    /**
     * @test
     */
    public function it_should_find_activation_and_throw_exception_when_activation_timed_out()
    {
        $this->setExpectedException(ActivationTimedOut::class);

        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->activation);
        $this->activation
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->activation
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(false);

        $this->activationService->checkToken(Email::fromString('local@domain.com'), ActivationCode::generate());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_during_activation_attempt_when_alias_or_code_are_invalid()
    {
        $this->setExpectedException(ActivationCodeIsInvalid::class);

        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->activation);
        $this->activation
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(false);

        $this->activationService->activate(
            Email::fromString('local@domain.com'),
            ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865')
        );
    }

    /**
     * @test
     */
    public function it_should_throw_exception_during_activation_attempt_when_alias_is_not_found()
    {
        $this->setExpectedException(AliasIsNotFound::class);

        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->activation);
        $this->activation
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->activation
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true);
        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);

        $this->activationService->activate(
            Email::fromString('local@domain.com'),
            ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865')
        );
    }

    /**
     * @test
     */
    public function it_should_activate_password_and_return_user()
    {
        $this->activationRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->activation);
        $this->activation
            ->shouldReceive('getCode')
            ->once()
            ->andReturn(new HashedActivationCode('valid_activation_code'));
        $this->codeHashingService
            ->shouldReceive('verify')
            ->once()
            ->andReturn(true);
        $this->activation
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true);
        $this->accountRepository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn($this->account);
        $this->accountRepository
            ->shouldReceive('update')
            ->once();
        $this->activationRepository
            ->shouldReceive('deleteByCode')
            ->once();

        $account = $this->activationService->activate(
            Email::fromString('local@domain.com'),
            ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865')
        );

        $this->assertInstanceOf(Account::class, $account);
    }

    public function tearDown()
    {
        m::close();
    }
}
