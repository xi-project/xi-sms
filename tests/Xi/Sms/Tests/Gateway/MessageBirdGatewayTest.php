<?php

namespace Xi\Sms\Tests\Gateway;

use MessageBird\Client;
use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\MessageBirdGateway;

class MessageBirdGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!getenv('RECEIVER_MSISDN')) {
            return $this->markTestSkipped('Receiver MSISDN must be set');
        }

        if (!getenv('MESSAGEBIRD_APIKEY')) {
            return $this->markTestSkipped('Api key must be set');
        }
    }

    /**
     * @test
     */
    public function sends()
    {
        $gateway = new MessageBirdGateway(getenv('MESSAGEBIRD_APIKEY'));

        $message = new SmsMessage(
            'Pekkis tassa lussuttaa.',
            '358503028030',
            '358503028030'
        );

        $ret = $gateway->send($message);
        $this->assertTrue($ret);
    }
}
