<?php

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;

class ClickatellGateway extends AbstractHttpRequestGateway
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

    public function __construct($apiKey, $user, $password, $endpoint = 'https://api.clickatell.com')
    {
        $this->apiKey = $apiKey;
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
            $url = "{$this->endpoint}/http/sendmsg?api_id={$this->apiKey}&user={$this->user}" .
                "&password={$this->password}&to={$to}&text={$body}&from={$from}";
            $this->getClient()->post($url, array());
        }
    }
}
