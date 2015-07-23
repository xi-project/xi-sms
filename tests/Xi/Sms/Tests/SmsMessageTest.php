<?php

namespace Xi\Sms;

use Xi\Sms\SmsMessage;

class SmsMessageTest extends \PHPUnit_Framework_TestCase
{
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
    public function canAddTo()
    {
        $message = new SmsMessage('Heippa hei', 'Tenhunen');

        $this->assertInternalType('array', $message->getTo());
        $this->assertCount(0, $message->getTo());

        $this->assertSame($message, $message->addTo('12345'));
        $this->assertSame(array('12345'), $message->getTo());
    }

    /**
     * @test
     */
    public function wontAddDuplicates()
    {
        $message = new SmsMessage(
            'Tussi',
            'Lussutaja',
            [
                '358503028030',
                '358503028030',
            ]
        );

        $this->assertSame(array('358503028030'), $message->getTo());

        $message->addTo('358503028030');
        $this->assertSame(array('358503028030'), $message->getTo());
    }
}
