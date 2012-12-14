<?php

namespace Xi\Sms\Gateway;

use Buzz\Browser;
use Buzz\Client\Curl;

/**
 * Convenience class for http sending gateways
 */
abstract class AbstractHttpRequestGateway implements GatewayInterface
{
    /**
     * @var Browser
     */
    private $client;

    /**
     * @param Browser $browser
     */
    public function setClient(Browser $browser)
    {
        $this->client = $browser;
    }

    /**
     * @return Browser
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Browser(new Curl());
        }
        return $this->client;
    }
}
