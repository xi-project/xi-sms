<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface GatewayInterface
{
    /**
     * Sends an SMS message. Fire and forget.
     *
     * @param SmsMessage $message
     */
    public function send(SmsMessage $message);

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher();
}
