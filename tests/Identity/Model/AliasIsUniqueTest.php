<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use Mockery as m;
use OG\Account\Domain\Identity\Model\AccountRepository;
use OG\Account\Domain\Identity\Model\AliasIsUnique;
use OG\Account\Domain\Identity\Model\Email;

class AliasIsUniqueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AccountRepository|\Mockery\Mock
     */
    private $repository;

    /**
     * @var AliasIsUnique
     */
    private $specification;

    public function setUp()
    {
        $this->repository = m::mock(AccountRepository::class);
        $this->specification = new AliasIsUnique($this->repository);
    }

    /**
     * @test
     */
    public function it_should_return_true_when_unique()
    {
        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(null);

        $this->assertTrue($this->specification->isSatisfiedBy(new Email('local@domain.com')));
    }

    /**
     * @test
     */
    public function it_should_return_false_when_not_unique()
    {
        $this->repository
            ->shouldReceive('findByAlias')
            ->once()
            ->andReturn(['id' => 1]);

        $this->assertFalse($this->specification->isSatisfiedBy(new Email('local@domain.com')));
    }

    public function tearDown()
    {
        m::close();
    }
}
