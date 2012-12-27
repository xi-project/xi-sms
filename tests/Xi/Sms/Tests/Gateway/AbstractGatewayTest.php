<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

class AbstractGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getEventDispatcherShouldReturnEventDispatcher()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $adapter = $this
            ->getMockBuilder('Xi\Sms\Gateway\AbstractGateway')
            ->setConstructorArgs(array($ed))
            ->setMethods(array('send'))
            ->getMockForAbstractClass();
        $this->assertSame($ed, $adapter->getEventDispatcher());
    }
}
