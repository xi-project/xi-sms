<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\NumberLimitingGateway;

class NumberLimitingGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function decoratorShouldDelegateWhenNoBlacklistsOrWhitelists()
    {
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $gateway = new NumberLimitingGateway($innerGateway);

        $message = new SmsMessage('Body moving', 'BodyMover', '358503028030');

        $self = $this;

        $innerGateway
            ->expects($this->once())
            ->method('send')
            ->with($message)
            ->will(
                $this->returnCallback(
                    function (SmsMessage $message) use ($self) {
                        $self->assertCount(1, $message->getTo());
                    }
                )
            );
        $gateway->send($message);

    }


    /**
     * @test
     */
    public function decoratorShouldNotDelegateWhenNoReceiversAreLeft()
    {
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $gateway = new NumberLimitingGateway($innerGateway, array(), array('#^358503028030$#'));

        $message = new SmsMessage('Body moving', 'BodyMover', '358503028030');

        $innerGateway
            ->expects($this->never())
            ->method('send');

        $gateway->send($message);
    }

    /**
     * @test
     */
    public function onlyTheStrongestShouldRemainAfterWhitelistingAndBlacklisting()
    {
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $gateway = new NumberLimitingGateway(
            $innerGateway,
            array('#^358#'),
            array('#^3585030280(30|31)$#', '#666$#')
        );

        $message = new SmsMessage('Body moving', 'BodyMover', '358503028031');
        $message->addTo('358403028030');

        $message->addTo('359503028030');
        $message->addTo('358503028032');

        $message->addTo('3593028030');
        $message->addTo('563028030');

        $self = $this;
        $innerGateway
            ->expects($this->once())
            ->method('send')
            ->with($message)
            ->will(
                $this->returnCallback(
                    function (SmsMessage $message) use ($self) {
                        $self->assertCount(2, $message->getTo());
                    }
                )
            );
        $gateway->send($message);


    }
}
