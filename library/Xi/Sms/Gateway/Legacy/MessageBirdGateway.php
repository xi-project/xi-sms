<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway\Legacy;

use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\BaseHttpRequestGateway;

class MessageBirdGateway extends BaseHttpRequestGateway
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $endpoint;

    private $gateway = 1;
    private $type = 'normal';

    public function __construct(
        $user,
        $password,
        $endpoint = 'https://api.messagebird.com'
    ) {
        $this->user = $user;
        $this->password = $password;
        $this->endpoint = $endpoint;
    }

    /**
     * @see GatewayInterface::send
     * @todo Implement a smarter method of sending (batch)
     */
    public function send(SmsMessage $message)
    {
        $body = urlencode(utf8_decode($message->getBody()));
        $from = urlencode($message->getFrom());

        foreach ($message->getTo() as $to) {
            $url = "{$this->endpoint}/xml/sms" .
                '?gateway=' . urlencode($this->gateway) .
                '&username=' . urlencode($this->user) .
                '&password=' . urlencode($this->password) .
                '&originator=' . $from .
                '&recipients=' . urlencode($to) .
                '&type=' . urlencode($this->type) .
                '&message=' . $body;
            $ret = $this->getClient()->post($url, array());
        }

        return true;
    }
}
