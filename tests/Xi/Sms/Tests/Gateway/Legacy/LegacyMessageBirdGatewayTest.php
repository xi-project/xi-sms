<?php

namespace Xi\Sms\Tests\Gateway\Legacy;

use Xi\Sms\Gateway\Legacy\MessageBirdGateway;
use Xi\Sms\SmsMessage;

class LegacyMessageBirdGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sends()
    {

        $browser = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();

        $browser
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://api.messagebird.com/xml/sms?gateway=1&username=username&password=password&originator=Tietoisku&recipients=3581234567&type=normal&message=Tenhunen+lipaisee',
                array()
            );

        $gateway = new MessageBirdGateway('username', 'password');

        $gateway->setClient($browser);

        $message = new SmsMessage(
            'Tenhunen lipaisee',
            'Tietoisku',
            '3581234567'
        );

        $ret = $gateway->send($message);
        $this->assertTrue($ret);
    }
}
