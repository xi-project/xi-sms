<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Event;

use Symfony\Component\EventDispatcher\Event;
use Xi\Sms\SmsMessage;

class SmsMessageEvent extends Event
{
    /**
     * @var SmsMessage
     */
    private $message;

    /**
     * @param SmsMessage $message
     */
    public function __construct(SmsMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @return SmsMessage
     */
    public function getMessage()
    {
        return $this->message;
    }
}
