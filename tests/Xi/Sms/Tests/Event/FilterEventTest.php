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
        $message = new SmsMessage('Tussi', 'Lussutaja', '358503028030');
        $filter = $this->getMock('Xi\Sms\Filter\FilterInterface');
        $event = new FilterEvent($message, $filter);
        $this->assertSame($message, $event->getMessage());
        $this->assertSame($filter, $event->getFilter());
    }
}
