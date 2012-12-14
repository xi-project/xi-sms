<?php

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;

interface GatewayInterface
{
    /**
     * Sends an SMS message. Fire and forget.
     *
     * @param SmsMessage $message
     */
    public function send(SmsMessage $message);
}
