<?php
namespace Germania\Mailer;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use \Swift_SmtpTransport;
use \Swift_Mailer;
use \Swift_Message;

use Psr\Log\NullLogger;

use Germania\SwiftMailerCallable\SwiftMailerCallable;

class MailerServiceProvider implements ServiceProviderInterface
{

    protected $defaultConfig = array(
        'smtp' => null,
        'port' => null,
        'ssl' => null,
        'user' => null,
        'pass' => null,
        'from_name' => null,
        'from_mail' => null,
        'to' => null,
        'subject' => null
    );


    public function __construct( array $mailer_config = array() )
    {
        $this->defaultConfig = array_merge($this->defaultConfig, $mailer_config);
    }


    /**
     * @implements ServiceProviderInterface
     */
    public function register(Container $dic)
    {



        if (!isset($dic['Mailer.Config'])) {
            $dic['Mailer.Config'] = function( $dic ) {
                return $this->defaultConfig;
            };
        } else {
            $dic->extend('Mailer.Config', function($existing_config, $dic ) {
                return array_merge($this->defaultConfig, $existing_config);
            });
        }



        /**
         * @return LoggerInterface|NullLogger
         */
        $dic['Mailer.Logger'] = function( $dic ) {
            return new NullLogger;
        };


        /**
         * @return Swift_SmtpTransport
         */
        $dic[Swift_SmtpTransport::class] = function( $dic ) {
            $mail_config = $dic['Mailer.Config'];

            $transport = new Swift_SmtpTransport(
                $mail_config['smtp'],
                $mail_config['port'],
                $mail_config['ssl'] ? 'ssl' : null);

            $transport->setUsername( $mail_config['user'] )
                      ->setPassword( $mail_config['pass'] );
            return $transport;
        };



        /**
         * @return \Swift_Mailer
         */
        $dic[Swift_Mailer::class] = function( $dic ) {
            $transport = $dic[Swift_SmtpTransport::class];
            return new Swift_Mailer( $transport );
        };


        // DEPRECATED
        $dic['SwiftMailer'] = function( $dic ) {
            return $dic[Swift_Mailer::class];
        };





        $dic[SwiftMessageFactory::class] = function($dic) {
            $mail_config = $dic['Mailer.Config'];

            $to = $mail_config['to'];
            $from = array( $mail_config['from_mail'] => $mail_config['from_name'] );
            $subject = $mail_config['subject'];

            return new SwiftMessageFactory($to, $from, $subject);
        };



        $dic[SwiftHtmlMessageFactory::class] = function($dic) {
            $mail_config = $dic['Mailer.Config'];

            $to = $mail_config['to'];
            $from = array( $mail_config['from_mail'] => $mail_config['from_name'] );
            $subject = $mail_config['subject'];

            return new SwiftHtmlMessageFactory($to, $from, $subject);
        };


        $dic[SwiftMessageFactoryInterface::class] = function($dic) {
            return $dic[SwiftHtmlMessageFactory::class];
        };




        /**
         * Container factory: Creates a new Swift_Message ON EACH CALL
         *
         * @return Swift_Message
         */
        $dic[Swift_Message::class] = $dic->factory(function( $dic ) {
            $factory = $dic[SwiftMessageFactory::class];
            return $factory->createMessage();
        });



        /**
         * Container factory: Creates a new Swift_Message ON EACH CALL
         *
         * @return Swift_Message
         */
        $dic['SwiftMailer.Message'] = $dic->factory(function( $dic ) {
            $factory = $dic[SwiftMessageFactory::class];
            return $factory->createMessage();
        });



        /**
         * Container factory: Creates a new Swift Html Message ON EACH CALL
         *
         * @return Swift_Message
         */
        $dic['SwiftMailer.HtmlMessage'] = $dic->factory(function( $dic ) {
            $factory = $dic[SwiftHtmlMessageFactory::class];
            return $factory->createMessage();
        });



        /**
         * @return Callable|SwiftMailerCallable
         */
        $dic[SwiftMailerCallable::class] = function($dic) {
            $mailer   = $dic['SwiftMailer'];
            $logger   = $dic['Mailer.Logger'];
            $factory  = $dic[SwiftMessageFactory::class];
            $callable = $dic[SwiftHtmlMessageFactory::class ];

            return new SwiftMailerCallable($mailer, $callable, $logger);
        };

        // DEPRECATED
        $dic['Mailer.Callable'] = function($dic) { return $dic[SwiftMailerCallable::class]; };


    }
}

