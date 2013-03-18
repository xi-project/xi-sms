<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use \Exception;
use Xi\Sms\SmsMessage;
use Xi\Sms\SmsException;
use Xi\Sms\Event\SmsMessageEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PixieGateway extends AbstractHttpRequestGateway
{
    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $endpoint;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        $account,
        $password,
        $endpoint = 'http://smsserver.pixie.se/sendsms'
    ) {
        parent::__construct($eventDispatcher);
        $this->account = $account;
        $this->password = $password;
        $this->endpoint = $endpoint;
    }

    /**
     * Sends an SMS message. Fire and forget.
     *
     * @param SmsMessage $message
     *
     * @return boolean Always returns true, regardless of the result
     */
    public function send(SmsMessage $message)
    {
        try
        {
            $this->sendOrThrowException($message);
        }
        catch(SmsException $e)
        {
            // Ignore the failure, notify event dispatcher anyways.
            $this->notifyEventDispatcher($message);
        }
        return true;
    }

    /**
     * Sends an SMS message
     *
     * @param SmsMessage $message
     *
     * @return void
     *
     * @throws SmsException if the SMS could not be sent
     */
    public function sendOrThrowException(SmsMessage $message)
    {
        $url = $this->generateUrl($message);
        $response = $this->getClient()->get($url, array());
        $result = $this->parseResponse($response);
        if ($result === true)
        {
            // Success
            $this->notifyEventDispatcher($message);
            return;
        }
        else
        {
            throw $result;
        }
    }

    /**
     * Generates endpoint URL to send message
     *
     * @param SmsMessage $message
     *
     * @return string The generated endpoint URL
     */
    private function generateUrl(SmsMessage $message)
    {
        $receivers = implode(',', $message->getTo());
        $signature = md5($this->account . implode(',', $message->getTo()) . $message->getBody() . $this->password);
        $sender = rawurlencode($message->getFrom());
        $body = rawurlencode($message->getBody());

        return $this->endpoint . "?account=$this->account&signature=$signature&".
            "receivers=$receivers&sender=$sender&message=$body";
    }

    /**
     * Notifies the event dispatcher
     *
     * @param SmsMessage $message
     */
    private function notifyEventDispatcher(SmsMessage $message)
    {
        $event = new SmsMessageEvent($message);
        $this->getEventDispatcher()->dispatch('xi_sms.send', $event);
    }

    /**
     * Parse response from the server
     *
     * @param \Buzz\Message\Response $response
     *
     * @return mixed Returns boolean true for success, or SmsException for errors
     */
    private function parseResponse(\Buzz\Message\Response $response)
    {
        $content = $response->getContent();

        $response = new \DOMDocument();
        $result = @$response->loadXml($content);
        if ($result === false)
        {
            return new SmsException('Could not parse XML response from Pixie', 100);
        }

        $code = $response->firstChild->getAttribute('code');
        if ($code == 0)
        {
            return true;
        }

        $message = $response->firstChild->getAttribute('description');

        return new SmsException($message, $code);
    }
}
