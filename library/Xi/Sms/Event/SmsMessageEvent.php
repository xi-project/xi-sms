<?php

namespace Xi\Sms\Event;

use Symfony\Component\EventDispatcher\Event;
use Xi\Sms\SmsMessage;

class SmsMessageEvent extends Event
{
    /**
     * @var SmsMessage
     */
    private $message;

    /**
     * @param SmsMessage $message
     */
    public function __construct(SmsMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @return SmsMessage
     */
    public function getMessage()
    {
        return $this->message;
    }

}
