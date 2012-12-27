<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Xi\Sms\SmsMessage;
use Xi\Sms\Event\SmsMessageEvent;
use XMLWriter;

/**
 * Infobip gateway
 */
class InfobipGateway extends AbstractHttpRequestGateway
{
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

    public function __construct(EventDispatcherInterface $eventDispatcher, $user, $password, $endpoint = 'https://api2.infobip.com/api')
    {
        parent::__construct($eventDispatcher);
        $this->user = $user;
        $this->password = $password;
        $this->endpoint = $endpoint;
    }

    /**
     * @see GatewayInterface::send
     */
    public function send(SmsMessage $message)
    {
        $writer = new XMLWriter();

        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');

        $writer->startElement('SMS');

        $writer->startElement('authentification');

        $writer->startElement('username');
        $writer->text($this->user);
        $writer->endElement();

        $writer->startElement('password');
        $writer->text($this->password);
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('message');

        $writer->startElement('sender');
        $writer->text($message->getFrom());
        $writer->endElement();

        $writer->startElement('text');
        $writer->text(utf8_decode($message->getBody()));
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('recipients');
        foreach ($message->getTo() as $to) {
            $writer->startElement('gsm');
            $writer->text($to);
            $writer->endElement();
        }
        $writer->endElement();

        $writer->endElement();
        $writer->endDocument();

        $this->getClient()->post($this->endpoint . '/sendsms/xml', array(), $writer->outputMemory());

        $event = new SmsMessageEvent($message);
        $this->getEventDispatcher()->dispatch('xi_sms.send', $event);

        return true;
    }
}
