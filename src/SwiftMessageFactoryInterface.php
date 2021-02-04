<?php
namespace Germania\Mailer;

interface SwiftMessageFactoryInterface
{


    /**
     * Callable invokation method for `createMessage()`
     *
     * @return \Swift_Message
     */
    public function __invoke();


    /**
     * @return \Swift_Message
     */
    public function createMessage();
}
