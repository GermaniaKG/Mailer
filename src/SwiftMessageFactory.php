<?php
namespace Germania\Mailer;

use \Swift_Message;

class SwiftMessageFactory implements SwiftMessageFactoryInterface  {

    protected $to = array();
    protected $from = array();
    protected $subject = "";

    public function __construct( $to, $from, $subject )
    {
        $this->setTo($to);
        $this->setFrom($from);
        $this->setSubject($subject);
    }


    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        return $this->createMessage();
    }


    /**
     * @inheritDoc
     */
    public function createMessage() {
        $message = new Swift_Message();

        $from = $this->getFrom();
        $to = $this->getTo();
        $subject = $this->getSubject();

        $message->setFrom( $from )
                ->setTo( $to )
                ->setSubject( $subject );

        return $message;
    }




    public function getSubject( ) {
        return $this->subject;
    }

    public function setSubject( $subject ) {

        if (is_string($subject) or is_null($subject)) {
            $this->subject = $subject;
            return $this;
        }
        $msg = sprintf("Expected string or NULL, got '%s'", gettype($subject));
        throw new \InvalidArgumentException($msg);
    }





    public function getFrom( ) {
        return $this->from;
    }

    public function setFrom( $email, $name = null) {

        if (is_string($email)) {
            $this->from = array( $email => $name);
            return $this;
        }

        if (is_array($email)) {
            $this->from = $email;
            return $this;
        }
        $msg = sprintf("Expected string or array, got '%s'", gettype($email));
        throw new \InvalidArgumentException($msg);
    }




    public function getTo( ) {
        return $this->to;
    }

    public function setTo( $to ) {
        if (is_string($to)) {
            $this->to = array($to);
        }
        elseif (is_array($to)) {
            $this->to = $to;
        }
        else {
            $msg = sprintf("Expected string or array, got '%s'", gettype($to));
            throw new \InvalidArgumentException($msg);
        }
        return $this;
    }

}
