<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\Account;
use OG\Account\Domain\Identity\Model\AccountId;
use OG\Account\Domain\Identity\Model\AccountIsLocked;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Core\Domain\Model\DateTime;

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
        $this->accountId = AccountId::fromString('3169e2af-90a3-49b3-a822-7854f05972ae');
        $this->email = new Email('local@domain.com');
        $this->password = new HashedPassword('valid_hashed_password');
    }

    /**
     * @test
     */
    public function it_should_create_new_account()
    {
        $creation_time = new DateTime('yesterday');

        DateTime::setTestDateTime($creation_time);
        $account = Account::create($this->accountId, $this->email, $this->password);
        DateTime::clearTestDateTime();

        $this->assertInstanceOf(Account::class, $account);
        $this->assertTrue($this->accountId->equals($account->getId()));
        $this->assertTrue($this->email->equals($account->getAlias()));
        $this->assertTrue($this->password->equals($account->getPassword()));
        $this->assertEquals($creation_time, $account->getCreatedAt());
        $this->assertEquals($creation_time, $account->getUpdatedAt());
        $this->assertFalse($account->isLocked());
        $this->assertFalse($account->isSoftLocked());
        $this->assertFalse($account->isHardLocked());
        $this->assertFalse($account->isEnabled());
        $this->assertEquals(1, count($account->releaseEvents()));

        return $account;
    }

    /**
     * @test
     * @depends it_should_create_new_account
     *
     * @param Account $account
     */
    public function it_should_reset_password($account)
    {
        $updated_time = new DateTime('tomorrow');
        $newPassword = new HashedPassword('new_password');

        DateTime::setTestDateTime($updated_time);
        $account->resetPassword($newPassword);
        DateTime::clearTestDateTime();

        $this->assertEquals(1, count($account->releaseEvents()));
        $this->assertEquals($newPassword, $account->getPassword());
        $this->assertEquals($updated_time, $account->getUpdatedAt());
    }

    /**
     * @test
     */
    public function it_should_not_reset_password_when_locked()
    {
        $this->setExpectedException(AccountIsLocked::class);

        $account = Account::create($this->accountId, $this->email, $this->password);
        $account->lockHard();
        $newPassword = new HashedPassword('new_password');

        $account->resetPassword($newPassword);
    }

    /**
     * @test
     * @depends it_should_create_new_account
     *
     * @param Account $account
     */
    public function it_should_activate_account($account)
    {
        $updated_time = new DateTime('tomorrow');

        DateTime::setTestDateTime($updated_time);
        $account->activate();
        DateTime::clearTestDateTime();

        $this->assertEquals(1, count($account->releaseEvents()));
        $this->assertEquals($updated_time, $account->getUpdatedAt());
    }

    /**
     * @test
     */
    public function it_should_not_activate_account_when_locked()
    {
        $this->setExpectedException(AccountIsLocked::class);

        $account = Account::create($this->accountId, $this->email, $this->password);
        $account->lockHard();

        $account->activate();
    }

    /**
     * @test
     * @depends it_should_create_new_account
     *
     * @param Account $account
     */
    public function it_should_change_password($account)
    {
        $updated_time = new DateTime('tomorrow');
        $newPassword = new HashedPassword('new_valid_hashed_password');

        DateTime::setTestDateTime($updated_time);
        $account->changePassword($newPassword);
        DateTime::clearTestDateTime();

        $this->assertEquals(1, count($account->releaseEvents()));
        $this->assertEquals($newPassword, $account->getPassword());
        $this->assertEquals($updated_time, $account->getUpdatedAt());
    }

    /**
     * @test
     */
    public function it_should_not_change_password_when_locked()
    {
        $this->setExpectedException(AccountIsLocked::class);

        $account = Account::create($this->accountId, $this->email, $this->password);
        $account->lockHard();
        $newPassword = new HashedPassword('new_valid_hashed_password');

        $account->changePassword($newPassword);
    }

    /**
     * @test
     *
     * @return Account
     */
    public function it_should_lock_soft()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);

        $account->lockSoft(new DateTime('tomorrow'));

        $this->assertTrue($account->isSoftLocked());
        $this->assertFalse($account->isHardLocked());
        $this->assertTrue($account->isLocked());

        return $account;
    }

    /**
     * @test
     *
     * @return Account
     */
    public function it_should_lock_hard()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);

        $account->lockHard();

        $this->assertFalse($account->isSoftLocked());
        $this->assertTrue($account->isHardLocked());
        $this->assertTrue($account->isLocked());

        return $account;
    }

    /**
     * @test
     */
    public function it_should_unlock()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);
        $account->lockSoft(new DateTime('tomorrow'));
        $account->lockHard();

        $account->unlock();

        $this->assertFalse($account->isSoftLocked());
        $this->assertFalse($account->isHardLocked());
        $this->assertFalse($account->isLocked());
    }

    /**
     * @test
     * @depends it_should_lock_soft
     *
     * @param Account $account
     */
    public function it_should_unlock_soft($account)
    {
        $account->unlock();

        $this->assertFalse($account->isSoftLocked());
        $this->assertFalse($account->isHardLocked());
        $this->assertFalse($account->isLocked());
    }

    /**
     * @test
     */
    public function it_should_unlock_soft_when_lock_time_has_passed()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);
        $account->lockSoft(new DateTime('tomorrow'));

        DateTime::setTestDateTime(new DateTime('tomorrow'));
        $this->assertFalse($account->isSoftLocked());
        $this->assertFalse($account->isHardLocked());
        $this->assertFalse($account->isLocked());
        DateTime::clearTestDateTime();
    }

    /**
     * @test
     * @depends it_should_lock_hard
     *
     * @param Account $account
     */
    public function it_should_unlock_hard($account)
    {
        $account->unlock();

        $this->assertFalse($account->isSoftLocked());
        $this->assertFalse($account->isHardLocked());
        $this->assertFalse($account->isLocked());
    }

    /**
     * @test
     */
    public function it_should_enable()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);
        $this->assertFalse($account->isEnabled());

        $account->enable();

        $this->assertTrue($account->isEnabled());
    }

    /**
     * @test
     */
    public function it_should_disable()
    {
        $account = Account::create($this->accountId, $this->email, $this->password);
        $account->enable();
        $this->assertTrue($account->isEnabled());

        $account->disable();

        $this->assertFalse($account->isEnabled());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = Account::create($this->accountId, $this->email, $this->password);
        $two = Account::create($this->accountId, $this->email, $this->password);
        $three = Account::create(AccountId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc'), $this->email, $this->password);

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
