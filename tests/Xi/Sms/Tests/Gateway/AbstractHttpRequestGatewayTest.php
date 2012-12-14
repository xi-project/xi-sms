<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

class AbstractHttpRequestGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getClientShouldCreateDefaultClient()
    {
        $adapter = $this->getMockForAbstractClass(
            'Xi\Sms\Gateway\AbstractHttpRequestGateway'
        );

        $client = $adapter->getClient();

        $this->assertInstanceOf('Buzz\Browser', $client);
    }

    public function getClientShouldObeySetter()
    {
        $adapter = $this->getMockForAbstractClass(
            'Xi\Sms\Gateway\AbstractHttpRequestGateway'
        );

        $client = $this->getMockBuilder('Buzz\Browser')->disableOriginalConstructor()->getMock();

        $adapter->setClient($client);

        $this->assertSame($client, $adapter->getClient());
    }
}
