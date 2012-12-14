<?php

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;

/**
 * Number limiting gateway decorator
 */
class NumberLimitingGateway implements GatewayInterface
{
    /**
     * @var GatewayInterface
     */
    private $gateway;

    /**
     * @var array
     */
    private $whitelisted = array();

    /**
     * @var array
     */
    private $blacklisted = array();

    /**
     * @param GatewayInterface $gateway
     * @param array $whitelisted An array of whitelist regexes
     * @param array $blacklisted An array of blacklist regexes
     */
    public function __construct(GatewayInterface $gateway, $whitelisted = array(), $blacklisted = array())
    {
        $this->gateway = $gateway;
        $this->whitelisted = $whitelisted;
        $this->blacklisted = $blacklisted;
    }

    /**
     * @see GatewayInterface::send
     */
    public function send(SmsMessage $message)
    {
        $to = $message->getTo();

        if ($this->whitelisted) {
            $to = array_filter($to, array($this, 'handleWhitelisted'));
        }

        if ($this->blacklisted) {
            $to = array_filter($to, array($this, 'handleBlacklisted'));
        }

        if (!$to) {
            return;
        }

        $message->setTo($to);
        return $this->gateway->send($message);
    }

    /**
     * @param $msisdn
     * @return bool
     */
    protected function handleWhitelisted($msisdn)
    {
        foreach ($this->whitelisted as $whitelisted) {
            if (preg_match($whitelisted, $msisdn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $msisdn
     * @return bool
     */
    protected function handleBlacklisted($msisdn)
    {
        foreach ($this->blacklisted as $blacklisted) {
            if (preg_match($blacklisted, $msisdn)) {
                return false;
            }
        }
        return true;
    }
}
