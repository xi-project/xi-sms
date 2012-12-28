<?php

namespace Xi\Sms\Gateway;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractGateway implements GatewayInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}
