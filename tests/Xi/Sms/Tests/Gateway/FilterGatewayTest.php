<?php

namespace Xi\Sms\Tests\Gateway;

use Xi\Sms\Gateway\FilterGateway;
use Xi\Sms\SmsMessage;

class FilterGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sendsWhenNoFilters()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($ed));

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
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($ed));

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

        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($ed));

        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $gateway = new FilterGateway($innerGateway);
        $gateway->addFilter($mockFilter);
        $gateway->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));
        $mockFilter2->expects($this->once())->method('accept')->with($message)->will($this->returnValue(false));

        $innerGateway->expects($this->never())->method('send');
        $ed
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                'xi_sms.filter.deny',
                $this->isInstanceOf('Xi\Sms\Event\FilterEvent')
            );



        $gateway->send($message);
    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndExitsEarlyIfDoesntGet()
    {
        $mockFilter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');

        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($ed));

        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $gateway = new FilterGateway($innerGateway);
        $gateway->addFilter($mockFilter);
        $gateway->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(false));
        $mockFilter2->expects($this->never())->method('accept');

        $innerGateway->expects($this->never())->method('send');
        $ed
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                'xi_sms.filter.deny',
                $this->isInstanceOf('Xi\Sms\Event\FilterEvent')
            );

        $gateway->send($message);
    }

    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndDelegatesToInnerGatewayWhenGetsIt()
    {
        $mockFilter = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Gateway\Filter\FilterInterface');

        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($ed));

        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $gateway = new FilterGateway($innerGateway);
        $gateway->addFilter($mockFilter);
        $gateway->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));
        $mockFilter2->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));

        $innerGateway->expects($this->once())->method('send')->with($message);

        $ed
            ->expects($this->never())
            ->method('dispatch');

        $gateway->send($message);
    }
}
