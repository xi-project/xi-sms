<?php

namespace Xi\Sms\Tests\Gateway;

use Xi\Sms\Gateway\MessageBirdGateway;

class MessageBirdGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsCorrectlyFormattedXmlToRightPlace()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $gateway = new MessageBirdGateway($ed, '', 'MY_USERNAME', 'MY_PASSWORD');

        $message = new \Xi\Sms\SmsMessage(
            'Hello world!',
            '31000000000',
            '31000000000'
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
