<?php

namespace Xi\Sms\Tests\Gateway;

class BaseHttpRequestGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getClientShouldCreateDefaultClient()
    {
        $adapter = $this
            ->getMockBuilder('Xi\Sms\Gateway\BaseHttpRequestGateway')
            ->getMockForAbstractClass();

        $client = $adapter->getClient();
        $this->assertInstanceOf('Buzz\Browser', $client);
    }

    /**
     * @test
     */
    public function getClientShouldObeySetter()
    {
        $adapter = $this
            ->getMockBuilder('Xi\Sms\Gateway\BaseHttpRequestGateway')
            ->getMockForAbstractClass();

        $client = $this->getMockBuilder('Buzz\Browser')->disableOriginalConstructor()->getMock();

        $adapter->setClient($client);

        $this->assertSame($client, $adapter->getClient());
    }
}
