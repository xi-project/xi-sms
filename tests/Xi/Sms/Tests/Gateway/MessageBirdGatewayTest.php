<?php

namespace Xi\Sms\Tests\Gateway;

use MessageBird\Client;
use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\MessageBirdGateway;

class MessageBirdGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!RECEIVER_MSISDN) {
            return $this->markTestSkipped('Receiver MSISDN must be set');
        }

        if (!MESSAGEBIRD_APIKEY) {
            return $this->markTestSkipped('Api key must be set');
        }
    }

    /**
     * @test
     */
    public function sends()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $gateway = new MessageBirdGateway($ed, MESSAGEBIRD_APIKEY);

        $message = new SmsMessage(
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
