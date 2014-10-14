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
     * Sends a fire and forget type SMS message.
     *
     * @param SmsMessage $message
     */
    public function send(SmsMessage $message);
}
