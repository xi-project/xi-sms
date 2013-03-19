<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway;

use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\PixieGateway;
use Buzz\Message\Response;

class PixieGatewayTest extends \PHPUnit_Framework_TestCase
{
    private function getMockSuccess()
    {
        $response = new Response();
        $response->setContent(
            '<?xml version="1.0" encoding = "ISO-8859-1" ?>'.
                '<response code="0"><cost>50</cost>'.
            '</response>');
        return $response;
    }

    private function getMockFailure($message, $code)
    {
        $response = new Response();
        $response->setContent(
            '<?xml version="1.0" encoding = "ISO-8859-1" ?>'.
                '<response code="'.$code.'" description="'.$message.'">'.
            '</response>');
        return $response;
    }

    private function getMockInvalidResponse()
    {
        $response = new Response();
        $response->setContent('<?not_valid_xml<');
        return $response;
    }

    private function getMockedGateway()
    {
        $ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $browser = $this->getMockedBrowser();
        $gateway = new PixieGateway($ed, 10203005, "DuY7ye99");
        $gateway->setClient($browser);
        return $gateway;
    }

    private function getMockedBrowser()
    {
        return $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function sendIgnoresExceptions()
    {
        $gateway = $this->getMockedGateway();
        $gateway->getClient()
            ->expects($this->once())
            ->method('get')
            ->will(
            $this->returnValue($this->getMockFailure('Some kind of failure', 123))
        );

        $gateway->getEventDispatcher()
            ->expects($this->once())
            ->method('dispatch')
            ->with(
            'xi_sms.send',
            $this->isInstanceOf('Xi\Sms\Event\SmsMessageEvent')
        );

        $message = new SmsMessage(
            'Hello world',
            'Santa Claus',
            array(12345678)
        );

        $result = $gateway->send($message);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function sendsCorrectRequest()
    {
        $gateway = $this->getMockedGateway();

        $gateway->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                'http://smsserver.pixie.se/sendsms?account=10203005&signature='.
                '2a6044ac52c48a4531ad5bc2022d3069&receivers=4670234567,463849235'.
                '&sender=Butiken&message=Rea%20i%20morgon.%20V%C3%A4lkommen',
                array())
            ->will(
            $this->returnValue($this->getMockSuccess())
        );

        $message = new SmsMessage(
            'Rea i morgon. Välkommen',
            'Butiken',
            array(4670234567,463849235)
        );

        $gateway->sendOrThrowException($message);
    }

    /**
     * @test
     */
    public function throwsSmsExceptionOnError()
    {
        $gateway = $this->getMockedGateway();
        $gateway->getClient()
            ->expects($this->once())
            ->method('get')
            ->will(
            $this->returnValue($this->getMockFailure("Too long sender name", 402))
        );

        $message = new SmsMessage(
            'Nice message',
            'Very long sender name',
            array(12345678)
        );

        $this->setExpectedException('\Xi\Sms\SmsException');

        $gateway->sendOrThrowException($message);
    }

    /**
     * @test
     */
    public function throwsSmsExceptionWithInvalidServerResponse()
    {
        $gateway = $this->getMockedGateway();
        $gateway->getClient()
            ->expects($this->once())
            ->method('get')
            ->will(
            $this->returnValue($this->getMockInvalidResponse())
        );

        $message = new SmsMessage(
            'Nice message',
            'Me',
            array(12345678)
        );

        $this->setExpectedException('\Xi\Sms\SmsException');

        $gateway->sendOrThrowException($message);
    }
}
