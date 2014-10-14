<?php

namespace Xi\Sms\Tests\Gateway;

use Xi\Sms\Gateway\ClickatellGateway;

class ClickatellGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsRequest()
    {
        $gateway = new ClickatellGateway('lussavain', 'lussuta', 'tussia', 'http://api.dr-kobros.com');

        $browser = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();

        $gateway->setClient($browser);

        $browser
            ->expects($this->once())
            ->method('post')
            ->with(
                'http://api.dr-kobros.com/http/sendmsg?api_id=lussavain&user=lussuta&password=' .
                'tussia&to=358503028030&text=Pekkis+tassa+lussuttaa.&from=358503028030',
                array()
            );

        $message = new \Xi\Sms\SmsMessage(
            'Pekkis tassa lussuttaa.',
            '358503028030',
            '358503028030'
        );

        $ret = $gateway->send($message);
        $this->assertTrue($ret);
    }
}
