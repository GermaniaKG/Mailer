<?php
namespace tests;

use Germania\Mailer\SwiftHtmlMessageFactory;
use Germania\Mailer\SwiftMessageFactoryInterface;
use \Swift_Message;

class SwiftHtmlMessageFactoryTest extends SwiftMessageFactoryTest
{
    public function testInstantiation()
    {
        $from = array("admin@test.com" => "Admin Name");
        $to = "me@test.com";
        $subject = null;


        $sut = new SwiftHtmlMessageFactory($to, $from, $subject);
        $this->assertInstanceOf(SwiftMessageFactoryInterface::class, $sut);

        return $sut;
    }


}
