<?php

namespace OG\Account\Test\Domain\Identity\Services;

use Mockery as m;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Services\DeleteAccountService;

class DeleteAccountServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AccountRepository|\Mockery\Mock
     */
    private $repository;

    /**
     * @var DeleteAccountService
     */
    private $service;

    public function setUp()
    {
        $this->repository = m::mock(AccountRepository::class);
        $this->service = new DeleteAccountService($this->repository);
    }

    /**
     * @test
     */
    public function it_should_delete_an_account()
    {
        $this->repository
            ->shouldReceive('delete')
            ->once();

        $this->service->deleteAccount(AccountId::generate());
    }

    public function tearDown()
    {
        m::close();
    }
}
