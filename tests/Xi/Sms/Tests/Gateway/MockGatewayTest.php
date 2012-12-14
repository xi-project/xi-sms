<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

use Xi\Sms\Gateway\MockGateway;

class MockGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsCorrectlyFormattedXmlToRightPlace()
    {
        $gateway = new MockGateway();

        $this->assertCount(0, $gateway->getSentMessages());

        $message = new \Xi\Sms\SmsMessage(
            'Tehdaan sovinto, pojat.',
            'Tenhunen',
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
