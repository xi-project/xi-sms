<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Event;

use Xi\Sms\SmsMessage;
use Xi\Sms\Filter\FilterInterface;

class FilterEvent extends SmsMessageEvent
{
    /**
     * @var FilterInterface
     */
    private $filter;

    public function __construct(SmsMessage $message, FilterInterface $filter)
    {
        parent::__construct($message);
        $this->filter = $filter;
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
