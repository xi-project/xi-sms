<?php

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;

/**
 * Mock gateway just stores the sent messages so they can be inspected at will.
 */
class MockGateway implements GatewayInterface
{
    /**
     * @var array
     */
    private $sentMessages = array();

    public function send(SmsMessage $message)
    {
        $this->sentMessages[] = $message;
    }

    /**
     * @return array
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }
}
