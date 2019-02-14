<?php
namespace tests;

use Germania\Mailer\MailerServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;

use \Swift_SmtpTransport;
use \Swift_Mailer;
use \Swift_Message;

class MailerServiceProviderTest extends \PHPUnit\Framework\TestCase
{	

	public function testInstantiation()
	{
		$sut = new MailerServiceProvider;
		$this->assertInstanceOf( ServiceProviderInterface::class, $sut);
	}


	public function testMailerConfigIsArray( )
	{
		$container = new Container;

		$sut = new MailerServiceProvider;
		$container->register( $sut );

		$config = $container['Mailer.Config'];
		$this->assertInternalType("array", $config);
	}

	public function testCallable( )
	{
		$container = new Container;

		$sut = new MailerServiceProvider;
		$container->register( $sut );

		$result = $container['Mailer.Callable'];
		$this->assertTrue( is_callable( $result ));
	}

	public function testMailerLogger( )
	{
		$container = new Container;

		$sut = new MailerServiceProvider;
		$container->register( $sut );

		$result = $container['Mailer.Logger'];
		$this->assertInstanceOf( LoggerInterface::class, $result);
	}

	public function testSwiftMailer( )
	{
		$container = new Container;

		$sut = new MailerServiceProvider;
		$container->register( $sut );

		$result = $container['SwiftMailer'];
		$this->assertInstanceOf( Swift_Mailer::class, $result);
	}

	public function testSwiftMailerMessage( )
	{
		$container = new Container;

		$sut = new MailerServiceProvider;
		$container->register( $sut );

		$container->extend('Mailer.Config', function($default) {
			$default['to'] = [ 'me@test.com'];
			$default['from_mail'] = 'me@test.com';
			return $default;
		});

		$result = $container['SwiftMailer.Message'];
		$this->assertInstanceOf( Swift_Message::class, $result);

		$result = $container['SwiftMailer.HtmlMessage'];
		$this->assertInstanceOf( Swift_Message::class, $result);
	}

}