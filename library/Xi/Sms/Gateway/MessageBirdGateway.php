<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;
use Xi\Sms\Event\SmsMessageEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use MessageBird\Client;
use MessageBird\Objects\Message;

class MessageBirdGateway extends AbstractGateway
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Client
     */
    private $client;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        $apiKey
    ) {
        parent::__construct($eventDispatcher);
        $this->apiKey = $apiKey;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->apiKey);
        }

        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @see GatewayInterface::send
     * @todo Implement a smarter method of sending (batch)
     */
    public function send(SmsMessage $message)
    {
        $msg = new Message();
        $msg->originator = $message->getFrom();
        $msg->recipients = $message->getTo();
        $msg->body = $message->getBody();

        $this->getClient()->messages->create($msg);

        $event = new SmsMessageEvent($message);
        $this->getEventDispatcher()->dispatch('xi_sms.send', $event);

        return true;
    }
}
