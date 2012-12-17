<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\Filter\FilterInterface;

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
     * @see GatewayInterface::send
     */
    public function send(SmsMessage $message)
    {
        foreach ($this->getFilters() as $filter) {
            if (!$filter->accept($message)) {
                return false;
            }
        }
        return $this->gateway->send($message);
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

