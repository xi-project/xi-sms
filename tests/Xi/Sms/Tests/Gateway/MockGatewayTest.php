<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

use Xi\Sms\Gateway\MockGateway;

use Xi\Sms\SmsMessage;

class MockGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsCorrectlyFormattedXmlToRightPlace()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $gateway = new MockGateway($ed);

        $this->assertCount(0, $gateway->getSentMessages());

        $message = new SmsMessage(
            'Tehdaan sovinto, pojat.',
            'Losoposki',
            '358503028030'
        );

        $gateway->send($message);
        $gateway->send($message);

        $this->assertCount(2, $gateway->getSentMessages());

        foreach ($gateway->getSentMessages() as $message) {
            $this->assertInstanceOf('Xi\Sms\SmsMessage', $message);
        }
    }
}
