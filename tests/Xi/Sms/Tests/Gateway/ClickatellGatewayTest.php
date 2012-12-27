<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

use Xi\Sms\Gateway\ClickatellGateway;

class ClickatellGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsCorrectlyFormattedXmlToRightPlace()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $gateway = new ClickatellGateway($ed, 'lussavain', 'lussuta', 'tussia', 'http://api.dr-kobros.com');

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

        $ed
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                'xi_sms.send',
                $this->isInstanceOf('Xi\Sms\Event\SmsMessageEvent')
            );

        $ret = $gateway->send($message);
        $this->assertTrue($ret);
    }
}
