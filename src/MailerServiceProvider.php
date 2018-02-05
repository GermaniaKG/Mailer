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

    /**
     * @implements ServiceProviderInterface
     */
    public function register(Container $dic)
    {


        /**
         * @return  StdClass
         */
        $dic['Mailer.Config'] = function( $dic ) {
            return [
                'smtp' => null,
                'port' => null,
                'ssl' => null,
                'user' => null,
                'pass' => null,
                'from_name' => null,
                'from_mail' => null,
                'to' => null,
                'subject' => null
            ];
        };


        /**
         * @return LoggerInterface|NullLogger
         */
        $dic['Mailer.Logger'] = function( $dic ) {
            return new NullLogger;
        };


        /**
         * @return Swift_SmtpTransport
         */
        $dic['SwiftMailer.Transport'] = function( $dic ) {
            $mail_config = $dic['Mailer.Config'];

            $transport = new Swift_SmtpTransport( $mail_config['smtp'], $mail_config['port'], $mail_config['ssl'] ? 'ssl' : null);

            $transport->setUsername( $mail_config['user'] )
                      ->setPassword( $mail_config['pass'] );
            return $transport;
        };



        /**
         * @return Swift_Mailer
         */
        $dic['SwiftMailer'] = function( $dic ) {
            $transport = $dic['SwiftMailer.Transport'];
            return new Swift_Mailer( $transport );
        };




        /**
         * @return Swift_Message
         */
        $dic['SwiftMailer.Message'] = $dic->factory(function( $dic ) {
            $mail_config = $dic['Mailer.Config'];
            $message = new Swift_Message();

            $from = array( $mail_config['from_mail'] => $mail_config['from_name'] );

            $to = is_string($mail_config['to']) ? array( $mail_config['to'] ) : $mail_config['to'];

            $message->setFrom( $from )
                    ->setTo( $to )
                    ->setSubject( $mail_config['subject'] );
            return $message;
        });



        /**
         * @return Swift_Message
         */
        $dic['SwiftMailer.HtmlMessage'] = $dic->factory(function( $dic ) {
            return $dic['SwiftMailer.Message']->setContentType( 'text/html');
        });



        /**
         * @return Callable
         */
        $dic['Mailer.Callable'] = $dic->factory(function( $dic ) {
            $mailer        = $dic['SwiftMailer'];
            $mailer_logger = $dic['Mailer.Logger'];

            $mailer_callable = new SwiftMailerCallable($mailer, function() use ($dic) {
                return $dic['SwiftMailer.HtmlMessage'];
            });

            $mailer_callable->setLogger( $mailer_logger );

            return $mailer_callable;
        });


    }
}

