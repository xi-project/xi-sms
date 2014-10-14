<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Filter;

use Xi\Sms\SmsMessage;

/**
 * Filter interface
 */
interface FilterInterface
{
    /**
     * @param SmsMessage $message
     * @return boolean
     */
    public function accept(SmsMessage $message);
}
