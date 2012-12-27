<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;
use Xi\Sms\Event\SmsMessageEvent;
use Xi\Sms\Gateway\Filter\FilterInterface;
use Xi\Sms\Event\FilterEvent;


/**
 * Filtering gateway decorator
 */
class FilterGateway implements GatewayInterface
{
    /**
     * @var array
     */
    private $filters = array();

    /**
     * @var GatewayInterface
     */
    private $gateway;

    /**
     * @param GatewayInterface $gateway
     */
    public function __construct(GatewayInterface $gateway, $filters = array())
    {
        $this->gateway = $gateway;
        $this->filters = $filters;
    }

    /**
     * @see GatewayInterface::getEventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->gateway->getEventDispatcher();
    }

    /**
     * @see GatewayInterface::send
     */
    public function send(SmsMessage $message)
    {
        foreach ($this->getFilters() as $filter) {
            if (!$filter->accept($message)) {
                $event = new FilterEvent($message, $filter);
                $this->getEventDispatcher()->dispatch('xi_sms.filter.deny', $event);
                return false;
            }
        }
        $ret = $this->gateway->send($message);
        $event = new SmsMessageEvent($message);
        $this->getEventDispatcher()->dispatch('xi_sms.send', $event);
        return $ret;
    }

    /**
     * @return array An array of filters
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

}

