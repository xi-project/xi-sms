<?php

namespace Xi\Sms\Tests\Gateway;

use Xi\Sms\Gateway\IpxGateway;
use Xi\Sms\SmsMessage;

/**
 * @group sms
 * @group ipx
 */
class IpxGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSend()
    {
        $soapResponse = json_decode(
            '{
            "correlationId": "randomUniqueString",
            "messageId": "1-17703562432",
            "responseCode": 0,
            "reasonCode": 0,
            "responseMessage": "Success",
            "temporaryError": false,
            "billingStatus": 2,
            "VAT": -1
            }'
        );
        $gateway = $this->getMockGateway($soapResponse);

        $body = 'testMessage';
        $from = '358010203';
        $to = '358504030201';

        $message = new SmsMessage($body, $from, $to);

        $this->assertTrue($gateway->send($message), 'Sending should have returned true');
    }

    /**
     * @test
     */
    public function testSendFail()
    {
        $soapResponse = json_decode(
            '{
            "correlationId": "randomUniqueString",
            "messageId": "",
            "responseCode": 107,
            "reasonCode": 0,
            "responseMessage": "Invalidalphanumericoriginatingaddress",
            "temporaryError": false,
            "billingStatus": 2,
            "VAT": -1
            }'
        );

        $gateway = $this->getMockGateway($soapResponse);

        $body = 'testMessage';
        $from = '358010203';
        $to = '358504030201';

        $message = new SmsMessage($body, $from, $to);

        $this->assertFalse($gateway->send($message), 'Sending should have failed');
    }

    /**
     * @test
     * @expectedException \invalidArgumentException
     */
    public function testSendInvalidFromException()
    {
        $body = 'Valid body';
        $from = 'Invalid sender';
        $to = '358501234567';
        $message = new SmsMessage($body, $from, $to);
        $this->getGateway()->send($message);
        $this->assertTrue(false, 'Sending should have thrown exception');
    }

    /**
     * @test
     * @dataProvider tonProvider
     */
    public function testTypeOfNumberParser($from, $ton)
    {
        $this->assertEquals($ton, IpxGateway::parseSenderTON($from));
    }

    /**
     * @return array
     */
    public function tonProvider()
    {
        /* TON:
         * 0 – Short number
         * 1 – Alpha numeric (max length 11)
         * 2 – MSISDN
         */
        return array(
            ['1', '0'],
            ['12', '0'],
            ['123', '0'],
            ['1234', '0'],
            ['12345', '0'],
            ['123456', '0'],
            ['1234567', '0'],
            ['12345678', '0'],
            ['123456789', '2'],
            ['1234567890', '2'],
            ['12345678901', '2'],
            ['123456789012', '2'],
            ['1234567890123', '2'],
            ['12345678901234', '2'],
            ['123456789012345', '2'],
            ['1234567890123456', '2'],
            ['12345678901234567', null],
            ['', '1'],
            ['a', '1'],
            ['ab', '1'],
            ['abc', '1'],
            ['abcd', '1'],
            ['abcde', '1'],
            ['abcdef', '1'],
            ['abcdefg', '1'],
            ['abcdefgh', '1'],
            ['abcdefghi', '1'],
            ['abcdefghij', '1'],
            ['abcdefghijk', '1'],
            ['abcdefghijkl', null],
            ['Koodi', '1'],
            ['ABC 123.fi', '1'],
            ['040 123 456', '1'],
        );
    }

    /**
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    public function testConstructorThrowExceptionOnInvalidConfig()
    {
        $eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        new IpxGateway($eventDispatcher, '', '', '', 1);
    }

    /**
     * @test
     */
    public function getClient()
    {
        $class = new \ReflectionClass($this->getGateway());
        $method = $class->getMethod('getClient');
        $method->setAccessible(true);

        $gateway = $this->getGateway();
        $client = $method->invokeArgs($gateway, []);
        $this->assertInstanceof('\SoapClient', $client);
    }

    protected function getGateway()
    {
        $wsdlUrl = "http://europe.ipx.com/api/services2/SmsApi52?wsdl";
        $username = 'notrealuser';
        $password = 'incorrectpassword';
        $timeout = 1;

        return new IpxGateway($wsdlUrl, $username, $password, $timeout);
    }

    /**
     * @return IpxGateway
     */
    protected function getMockGateway($soapResponse)
    {
        //Mock client
        $mockClient = $this->getMockBuilder('\SoapClient')
                ->disableOriginalConstructor()
                ->getMock();
        $mockClient->expects($this->once())->method('__soapCall')->will($this->returnValue($soapResponse));

        $wsdlUrl = 'http://example.com?wsdl';
        $username = 'username';
        $password = 'password';
        $timeout = 10;

        $mockGateway = $this->getMockBuilder('Xi\Sms\Gateway\IpxGateway')
            ->setMethods(['getClient'])
            ->setConstructorArgs([$wsdlUrl, $username, $password, $timeout])
            ->getMock();

        $mockGateway->expects($this->once())->method('getClient')->will($this->returnValue($mockClient));

        return $mockGateway;
    }
}
