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
        $this->assertIsArray($config);
    }



    /**
     * @dataProvider provideConfigurationOverrides
     */
	public function testMailerPreviousConfiguration( $override )
	{
		$container = new Container;
        $container['Mailer.Config'] = function($dic) use ($override) {
            return $override;
        };

		$container->register( new MailerServiceProvider() );

		$config = $container['Mailer.Config'];
		$this->assertIsArray($config);

        foreach($override as $field => $value ) {
            $this->assertArrayHasKey($field, $config);
            $this->assertEquals($value, $config[$field]);
        }
	}


    /**
     * @dataProvider provideConfigurationOverrides
     */
    public function testMailerConfigurationInConstructor( $override )
    {
        $container = new Container;

        $sut = new MailerServiceProvider($override);
        $container->register( $sut );

        $config = $container['Mailer.Config'];
        $this->assertIsArray($config);

        foreach($override as $field => $value ) {
            $this->assertArrayHasKey($field, $config);
            $this->assertEquals($value, $config[$field]);
        }
    }



    public function provideConfigurationOverrides()
    {
        return array(
            [ array('foo' => 'bar') ],
            [ array() ],
        );
    }



	public function testCallable( )
	{
		$container = new Container;

		$sut = new MailerServiceProvider;
		$container->register( $sut );

        $container->extend('Mailer.Config', function($default) {
            $default['to'] = [ 'me@test.com'];
            $default['from_mail'] = 'me@test.com';
            return $default;
        });

		$result = $container['Mailer.Callable'];
		$this->assertIsCallable( $result );
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

        $result = $container[Swift_Mailer::class];
        $this->assertInstanceOf( Swift_Mailer::class, $result);

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
