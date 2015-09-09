<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedPassword;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AccountId
     */
    private $accountId;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var HashedPassword
     */
    private $password;

    public function setUp()
    {
        $this->accountId = AccountId::generate();
        $this->email = new Email('local@domain.com');
        $this->password = new HashedPassword('valid_hashed_password');
    }

    /**
     * @test
     */
    public function it_should_require_user_id()
    {
        $this->setExpectedException('Exception');
        Account::create(null, $this->email, $this->password);
    }

    /**
     * @test
     */
    public function it_should_require_email()
    {
        $this->setExpectedException('Exception');
        Account::create($this->accountId, null, $this->password);
    }

    /**
     * @test
     */
    public function it_should_require_password()
    {
        $this->setExpectedException('Exception');
        Account::create($this->accountId, $this->email, null);
    }

    /**
     * @test
     */
    public function it_should_create_new_account()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);

        $this->assertInstanceOf(Account::class, $account);

        return $account;
    }

    /**
     * @test
     * @depends it_should_create_new_account
     *
     * @param Account $account
     */
    public function it_should_return_identifier(Account $account)
    {
        $this->assertInstanceOf(AccountId::class, $account->getId());
    }

    /**
     * @test
     * @depends it_should_create_new_account
     *
     * @param Account $account
     */
    public function it_should_return_alias(Account $account)
    {
        $this->assertInstanceOf(Email::class, $account->getAlias());
    }

    /**
     * @test
     * @depends it_should_create_new_account
     *
     * @param Account $account
     */
    public function it_should_return_password(Account $account)
    {
        $this->assertInstanceOf(HashedPassword::class, $account->getPassword());
    }
}
