<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway\Filter;

use Xi\Sms\SmsMessage;

/**
 * Number limiting
 */
class NumberLimitingFilter implements FilterInterface
{
    /**
     * @var array
     */
    private $whitelisted = array();

    /**
     * @var array
     */
    private $blacklisted = array();

    /**
     * @param array $whitelisted An array of whitelist regexes
     * @param array $blacklisted An array of blacklist regexes
     */
    public function __construct($whitelisted = array(), $blacklisted = array())
    {
        $this->whitelisted = $whitelisted;
        $this->blacklisted = $blacklisted;
    }

    /**
     * @see FilterInterface::accept
     */
    public function accept(SmsMessage $message)
    {
        $to = $message->getTo();

        if ($this->whitelisted) {
            $to = array_filter($to, array($this, 'handleWhitelisted'));
        }

        if ($this->blacklisted) {
            $to = array_filter($to, array($this, 'handleBlacklisted'));
        }
        return (bool) $to;
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
