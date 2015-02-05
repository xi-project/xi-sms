<?php

namespace Xi\Sms\Tests;

use Xi\Sms\SmsService;
use Xi\Sms\SmsMessage;

class SmsServiceTest extends \PHPUnit_Framework_TestCase
{
    
    private $innerGateway;
    private $service;
    private $message;
    private $eventDispatcher;
    private $mockFilter;

    public function setUp()
    {
        $this->innerGateway     = $this->getMock('Xi\Sms\Gateway\GatewayInterface');
        $this->eventDispatcher  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->mockFilter       = $this->getMock('Xi\Sms\Filter\FilterInterface');
        $this->service          = new SmsService($this->innerGateway, $this->eventDispatcher);
        $this->message          = new SmsMessage('Lussuhovi', '358503028030', '358503028031');
    }
    
    /**
     * @test
     */
    public function initializesDefaultEventDispatcher()
    {
        new SmsService($this->innerGateway);
    }

    /**
     * @test
     */
    public function sendsWhenNoFilters()
    {
        $this->innerGateway
            ->expects($this->once())
            ->method('send')
            ->with($this->message)
            ->will($this->returnValue(true));

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch');

        $this->assertTrue($this->service->send($this->message));
    }

    /**
     * @test
     */
    public function initializesWithFiltersAndAddsWithAdd()
    {
        $this->service
            ->addFilter($this->mockFilter)
            ->addFilter($this->mockFilter);

        $this->assertCount(2, $this->service->getFilters());

        $mockFilter = $this->mockFilter;

        $this->service->addFilter($mockFilter);

        $this->assertCount(3, $this->service->getFilters());

        foreach ($this->service->getFilters() as $filter) {
            $this->assertInstanceOf('Xi\Sms\Filter\FilterInterface', $filter);
        }
    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptance()
    {
        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $this->innerGateway->expects($this->any())->method('getEventDispatcher')->will($this->returnValue($this->eventDispatcher));

        $this->service->addFilter($this->mockFilter);
        $this->service->addFilter($mockFilter);

        $this->mockFilter->expects($this->once())->method('accept')->with($this->message)->will($this->returnValue(true));
        $mockFilter->expects($this->once())->method('accept')->with($this->message)->will($this->returnValue(false));

        $this->innerGateway->expects($this->never())->method('send');
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                'xi_sms.filter.deny',
                $this->isInstanceOf('Xi\Sms\Event\FilterEvent')
            );

        $this->service->send($this->message);
    }


    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndExitsEarlyIfDoesntGet()
    {
        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $this->service->addFilter($this->mockFilter);
        $this->service->addFilter($mockFilter);

        $this->mockFilter->expects($this->once())->method('accept')->with($this->message)->will($this->returnValue(false));
        $mockFilter->expects($this->never())->method('accept');

        $this->innerGateway->expects($this->never())->method('send');
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                'xi_sms.filter.deny',
                $this->isInstanceOf('Xi\Sms\Event\FilterEvent')
            );

        $this->service->send($this->message);
    }

    /**
     * @test
     */
    public function begsAllFiltersForAcceptanceAndDelegatesToInnerGatewayWhenGetsIt()
    {
        $mockFilter = $this->getMock('Xi\Sms\Filter\FilterInterface');

        $this->service->addFilter($this->mockFilter);
        $this->service->addFilter($mockFilter);

        $this->mockFilter->expects($this->once())->method('accept')->with($this->message)->will($this->returnValue(true));
        $mockFilter->expects($this->once())->method('accept')->with($this->message)->will($this->returnValue(true));

        $this->innerGateway->expects($this->once())->method('send')->with($this->message);

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $this->service->send($this->message);
    }

}
