<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms;

/**
 * SMS message
 */
class SmsMessage
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $from;

    /**
     * @var array
     */
    private $to = array();

    /**
     * @param string $body
     * @param string $from
     * @param array|string $to
     */
    public function __construct($body, $from, $to)
    {
        $this->body = $body;
        $this->from = $from;
        $this->to   = $this->checkTo($to);
    }
    
    public function __call($function, $args)
    {
        $arguments = implode(', ', $args);
        switch ($function) {
            case 'setBody':
                $this->body = $arguments;
                break;
            case 'setFrom' :
                $this->from = $arguments;
                break;
            case 'setTo' :
                $this->to = $this->checkTo($arguments);
                break;
            default:
                throw new \Exception('Invalid Method');
                
        }
    }

    /**
     * @return null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets receiver or an array of receivers
     *
     * @param string|array $to
     * @return array $to
     */
    private function checkTo($to)
    {
        if (!is_array($to)) {
            $to = array($to);
        }
        return $to;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function addTo($to)
    {
        $this->to[] = $to;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
}
