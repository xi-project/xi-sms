<?php

namespace Xi\Sms\Tests;

use Xi\Sms\SmsService;
use Xi\Sms\SmsMessage;

class SmsServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function initializesDefaultEventDispatcher()
    {
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        new SmsService($innerGateway);
    }

    /**
     * @test
     */
    public function sendsWhenNoFilters()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $service = new SmsService($innerGateway, $ed);
        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $innerGateway
            ->expects($this->once())
            ->method('send')
            ->with($message)
            ->will($this->returnValue(true));

        $ed
            ->expects($this->once())
            ->method('dispatch');

        $ret = $service->send($message);
        $this->assertTrue($ret);
    }

    /**
     * @test
     */
    public function initializesWithFiltersAndAddsWithAdd()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');

        $service = new SmsService(
            $innerGateway, $ed
        );

        $service
            ->addFilter($this->getMock('Xi\Sms\Filter\FilterInterface'))
            ->addFilter($this->getMock('Xi\Sms\Filter\FilterInterface'));

        $this->assertCount(2, $service->getFilters());

        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $service->addFilter($mockFilter);

        $this->assertCount(3, $service->getFilters());

        foreach ($service->getFilters() as $filter) {
            $this->assertInstanceOf('Xi\Sms\Filter\FilterInterface', $filter);
        }
    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptance()
    {
        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($ed));

        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $service = new SmsService($innerGateway, $ed);
        $service->addFilter($mockFilter);
        $service->addFilter($mockFilter2);

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

        $service->send($message);
    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndExitsEarlyIfDoesntGet()
    {
        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');

        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $service = new SmsService($innerGateway, $ed);
        $service->addFilter($mockFilter);
        $service->addFilter($mockFilter2);

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

        $service->send($message);
    }

    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndDelegatesToInnerGatewayWhenGetsIt()
    {
        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');
        $mockFilter2 = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $innerGateway = $this->getMock('Xi\Sms\Gateway\GatewayInterface');

        $message = new SmsMessage('Lussuhovi', '358503028030', '358503028031');

        $service = new SmsService($innerGateway, $ed);
        $service->addFilter($mockFilter);
        $service->addFilter($mockFilter2);

        $mockFilter->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));
        $mockFilter2->expects($this->once())->method('accept')->with($message)->will($this->returnValue(true));

        $innerGateway->expects($this->once())->method('send')->with($message);

        $ed
            ->expects($this->never())
            ->method('dispatch');

        $service->send($message);
    }

}
