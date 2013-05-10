<?php

namespace Xi\Sms\Tests\Gateway;

class AbstractHttpRequestGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getClientShouldCreateDefaultClient()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $adapter = $this
            ->getMockBuilder('Xi\Sms\Gateway\AbstractHttpRequestGateway')
            ->setConstructorArgs(array($ed))
            ->getMockForAbstractClass();

        $client = $adapter->getClient();
        $this->assertInstanceOf('Buzz\Browser', $client);
    }

    /**
     * @test
     */
    public function getClientShouldObeySetter()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $adapter = $this
            ->getMockBuilder('Xi\Sms\Gateway\AbstractHttpRequestGateway')
            ->setConstructorArgs(array($ed))
            ->getMockForAbstractClass();

        $client = $this->getMockBuilder('Buzz\Browser')->disableOriginalConstructor()->getMock();

        $adapter->setClient($client);

        $this->assertSame($client, $adapter->getClient());
    }
}
