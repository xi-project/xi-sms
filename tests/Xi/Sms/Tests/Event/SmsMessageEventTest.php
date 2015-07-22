<?php

namespace Xi\Sms\Tests\Event;

use Xi\Sms\SmsMessage;
use Xi\Sms\Event\SmsMessageEvent;

class SmsMessageEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getMessageShouldReturnMessage()
    {
        $message = new SmsMessage('Tussi', 'Lussutaja', '358503028030');
        $event = new SmsMessageEvent($message);
        $this->assertSame($message, $event->getMessage());
    }
}
