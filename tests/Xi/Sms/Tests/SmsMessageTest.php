<?php

namespace Xi\Sms;

use Xi\Sms\SmsMessage;

class SmsMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Sms\SmsMessage'));
    }

    /**
     * @test
     */
    public function messageShouldInitializeViaConstructor()
    {
        $message = new SmsMessage('Tussi', 'Lussutaja', '358503028030');

        $this->assertSame('Tussi', $message->getBody());
        $this->assertSame('Lussutaja', $message->getFrom());
        $this->assertSame(array('358503028030'), $message->getTo());

    }

    /**
     * @test
     */
    public function settersAndGettersShouldWork()
    {
        $message = new SmsMessage('Tussi', 'Lussutaja', '358503028030');
        $message->addTo('358503028030');

        $this->assertEquals(array('358503028030', '358503028030' ), $message->getTo());

        $message->addTo('35850666');
        $this->assertEquals(array('358503028030', '358503028030', '35850666'), $message->getTo());
    }
}
