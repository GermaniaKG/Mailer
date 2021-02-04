<?php
namespace tests;

use Germania\Mailer\SwiftMessageFactory;
use Germania\Mailer\SwiftMessageFactoryInterface;
use \Swift_Message;

class SwiftMessageFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testInstantiation()
    {
        $from = array("admin@test.com" => "Admin Name");
        $to = "me@test.com";
        $subject = null;

        $sut = new SwiftMessageFactory($to, $from, $subject);
        $this->assertInstanceOf(SwiftMessageFactoryInterface::class, $sut);

        return $sut;
    }


    /**
     * @depends testInstantiation
     * @dataProvider provideVariousSubjectValues
     */
    public function testSubjectInterceptors($subject, $sut)
    {
        $result = $sut->setSubject($subject);
        $this->assertEquals($result, $sut);
        $this->assertEquals($subject, $sut->getSubject());
    }


    public function provideVariousSubjectValues() {
        return array(
            [ "Test Subject line" ],
            [ null ],
        );
    }

    /**
     * @depends testInstantiation
     * @dataProvider provideVariousToValues
     */
    public function testToInterceptors($to, $sut)
    {
        $result = $sut->setTo($to);
        $this->assertEquals($result, $sut);
        $this->assertIsArray($sut->getTo());
    }


    public function provideVariousToValues() {
        return array(
            [ "me@test.com" ]
        );
    }


    /**
     * @depends testInstantiation
     * @dataProvider provideVariousFromValues
     */
    public function testFromInterceptors($from, $sut)
    {
        $result = $sut->setFrom($from);
        $this->assertEquals($result, $sut);
        $this->assertIsArray($sut->getFrom());
    }


    public function provideVariousFromValues() {
        return array(
            [ "me@test.com" ],
            [ array("admin@test.com" => "Admin Name") ]
        );
    }



    /**
     * @depends testInstantiation
     */
    public function testMessageCreation( $sut )
    {
        $msg = $sut->createMessage();
        $this->assertInstanceOf(Swift_Message::class, $msg);

        $msg = $sut();
        $this->assertInstanceOf(Swift_Message::class, $msg);
    }
}
