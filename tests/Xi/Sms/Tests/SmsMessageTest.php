<?php

namespace Xi\Sms;

use Xi\Sms\SmsMessage;

class SmsMessageTest extends \PHPUnit_Framework_TestCase
{
    private $message;
    
    public function setUp()
    {
        $this->message = new SmsMessage('Tussi', 'Lussutaja', '358503028030');
    }
    
    /**
     * @test
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Sms\SmsMessage'));
    }

    /**
     * @test
     */
    public function messageShouldInitializeViaConstructor()
    {
        $this->assertSame('Tussi', $this->message->getBody());
        $this->assertSame('Lussutaja', $this->message->getFrom());
        $this->assertSame(array('358503028030'), $this->message->getTo());

    }

    /**
     * @test
     */
    public function settersAndGettersShouldWork()
    {
        $this->assertNotNull($this->message->getFrom());
        $this->assertNotNull($this->message->getBody());
        $this->assertEquals(array('358503028030'), $this->message->getTo());

        $this->message->setFrom('Losoposki');
        $this->message->setBody('Ollaanko kavereita?');
        $this->message->addTo('358503028030');
        
        $this->assertEquals('Losoposki', $this->message->getFrom());
        $this->assertEquals('Ollaanko kavereita?', $this->message->getBody());
        $this->assertEquals(array('358503028030', '358503028030'), $this->message->getTo());

        $this->message->addTo('35850666');
        $this->assertEquals(array('358503028030', '358503028030', '35850666'), $this->message->getTo());

        $this->message->setTo('358503028031');
        $this->assertEquals(array('358503028031'), $this->message->getTo());
    }
    
    /**
     * @test
     * 
     * @expectedException Exception
     * @expectedExceptionMessage Invalid Method
     */
    public function callingInvalidMethod()
    {
        $this->message->methodThatDoesNotExists();
    }
}