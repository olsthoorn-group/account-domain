<?php

namespace OG\Account\Test\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedActivationCode;
use OG\Account\Domain\Identity\Model\Activation;
use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Account\Domain\Identity\Model\ActivationId;
use OG\Core\Domain\Model\DateTime;

class ActivationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ActivationId
     */
    private $id;

    /**
     * @var Email
     */
    private $alias;

    /**
     * @var ActivationCode
     */
    private $code;

    /**
     * @var HashedActivationCode
     */
    private $hashedCode;

    public function setUp()
    {
        $this->id = ActivationId::fromString('3169e2af-90a3-49b3-a822-7854f05972ae');
        $this->alias = new Email('local@domain.com');
        $this->code = ActivationCode::generate();
        $this->hashedCode = new HashedActivationCode('valid_activation_code');
    }

    /**
     * @test
     */
    public function it_should_create_activation()
    {
        $creation_time = DateTime::now();

        $activation = Activation::request($this->id, $this->alias, $this->code, $this->hashedCode);

        $this->assertInstanceOf(Activation::class, $activation);
        $this->assertEquals($this->id, $activation->getId());
        $this->assertEquals($this->alias, $activation->getAlias());
        $this->assertEquals($this->hashedCode, $activation->getCode());
        $this->assertEquals($creation_time, $activation->getCreatedAt());
        $this->assertEquals(1, count($activation->releaseEvents()));
    }

    /**
     * @test
     */
    public function it_should_be_valid_when_not_expired()
    {
        $activation = Activation::request($this->id, $this->alias, $this->code, $this->hashedCode);

        $this->assertTrue($activation->isValid());
    }

    /**
     * @test
     */
    public function it_should_be_invalid_when_expired()
    {
        DateTime::setTestDateTime(new DateTime('yesterday'));
        $activation = Activation::request($this->id, $this->alias, $this->code, $this->hashedCode);
        DateTime::clearTestDateTime();

        $this->assertFalse($activation->isValid());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = Activation::request($this->id, $this->alias, $this->code, $this->hashedCode);
        $two = Activation::request($this->id, $this->alias, $this->code, $this->hashedCode);
        $three = Activation::request(ActivationId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc'), $this->alias, $this->code, $this->hashedCode);

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
