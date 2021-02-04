<?php
namespace Germania\Mailer;

use \Swift_Message;

class SwiftHtmlMessageFactory extends SwiftMessageFactory implements SwiftMessageFactoryInterface {


    /**
     * @inheritDoc
     */
    public function createMessage()
    {
        $message = parent::createMessage();
        $message->setContentType( 'text/html');
        return $message;
    }


}
