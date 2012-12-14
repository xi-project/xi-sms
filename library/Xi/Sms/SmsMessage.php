<?php

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

    public function __construct($body = null, $from = null, $to = array())
    {
        $this->body = $body;
        $this->from = $from;

        if ($to) {
            $this->setTo($to);
        }
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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
     */
    public function setTo($to)
    {
        if (!is_array($to)) {
            $to = array($to);
        }
        $this->to = $to;
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
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
}
