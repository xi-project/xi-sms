<?php

namespace Xi\Sms\Tests\Event;

use Xi\Sms\SmsMessage;
use Xi\Sms\Event\FilterEvent;

class FilterEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getMessageShouldReturnMessage()
    {
        $message = new SmsMessage('Tussi');
        $filter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');
        $event = new FilterEvent($message, $filter);
        $this->assertSame($message, $event->getMessage());
        $this->assertSame($filter, $event->getFilter());
    }
}
