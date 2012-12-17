<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

use Xi\Sms\Gateway\FilterGateway;
use Xi\Sms\SmsMessage;

class FilterGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsWhenNoFilters()
    {
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $gateway = new FilterGateway($innerGateway);
        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $innerGateway->expects($this->once())->method('send')->with($message);

        $gateway->send($message);
    }

    /**
     * @test
     */
    public function initializesWithFiltersAndAddsWithAdd()
    {
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');

        $gateway = new FilterGateway(
            $innerGateway,
            array(
                $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface'),
                $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface')
            )
        );

        $this->assertCount(2, $gateway->getFilters());

        $mockFilter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');

        $gateway->addFilter($mockFilter);

        $this->assertCount(3, $gateway->getFilters());

        foreach ($gateway->getFilters() as $filter) {
            $this->assertInstanceOf('Xi\Sms\Gateway\Filter\FilterInterface', $filter);
        }

    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptance()
    {
        $mockFilter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');

        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $gateway = new FilterGateway($innerGateway);
        $gateway->addFilter($mockFilter);
        $gateway->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));
        $mockFilter2->expects($this->once())->method('accept')->with($message)->will($this->returnValue(false));

        $innerGateway->expects($this->never())->method('send');

        $gateway->send($message);
    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndExitsEarlyIfDoesntGet()
    {
        $mockFilter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');

        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $gateway = new FilterGateway($innerGateway);
        $gateway->addFilter($mockFilter);
        $gateway->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(false));
        $mockFilter2->expects($this->never())->method('accept');

        $innerGateway->expects($this->never())->method('send');

        $gateway->send($message);
    }

    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndDelegatesToInnerGatewayWhenGetsIt()
    {
        $mockFilter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');

        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $gateway = new FilterGateway($innerGateway);
        $gateway->addFilter($mockFilter);
        $gateway->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));
        $mockFilter2->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));

        $innerGateway->expects($this->once())->method('send')->with($message);

        $gateway->send($message);
    }
}
