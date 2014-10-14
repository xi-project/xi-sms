<?php

namespace Xi\Sms;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Xi\Sms\Event\Events;
use Xi\Sms\Event\FilterEvent;
use Xi\Sms\Event\SmsMessageEvent;
use Xi\Sms\Filter\FilterInterface;
use Xi\Sms\Gateway\GatewayInterface;

class SmsService
{
    /**
     * @var EventDispatcherInterface
     */
    private $ed;

    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * @param GatewayInterface $gateway
     */
    public function __construct(GatewayInterface $gateway, EventDispatcherInterface $ed = null)
    {
        if (!$ed) {
            $ed = new EventDispatcher();
        }

        $this->gateway = $gateway;
        $this->ed = $ed;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param SmsMessage $message
     * @return bool
     */
    public function send(SmsMessage $message)
    {

        foreach ($this->getFilters() as $filter) {
            /** @var FilterInterface $filter */
            if (!$filter->accept($message)) {
                $event = new FilterEvent($message, $filter);
                $this->ed->dispatch(Events::FILTER_DENY, $event);
                return false;
            }
        }

        $ret = $this->gateway->send($message);

        if ($ret) {
            $event = new SmsMessageEvent($message);
            $this->ed->dispatch(Events::SEND, $event);
        }

        return $ret;
    }
}

