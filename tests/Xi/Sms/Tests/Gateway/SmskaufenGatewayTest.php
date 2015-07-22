<?php

namespace Xi\Sms\Tests\Gateway;

use Xi\Sms\SmsMessage;
use Xi\Sms\SmsService;
use Xi\Sms\SmsException;
use Xi\Sms\Gateway\SmskaufenGateway;
use Buzz\Message\Response;

class SmskaufenGatewayTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function sendMass2() {

		$this->SmskaufenGateway = new SmskaufenGateway(array(
			'username' => 'XXX',
			'password' => 'YYY',
			'gateway' => 13
		));

		$service = new SmsService($this->SmskaufenGateway);

		$msg = new SmsMessage('Hi', '00491234', array('00491111', '015111111', '0170111111'));
		$this->setExpectedException('Xi\Sms\SmsException');
		$service->send($msg);
	}

	/**
	 * @test
	 */
	public function sendMass1() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('getContent', 'isOk'));
		$ResponseMock
			->expects($this->once())
			->method('isOk')
			->will($this->returnValue(false));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);
		$msg = new SmsMessage('Hi', '00491234', array('00491111', '015111111', '0170111111'));
		$service->send($msg); // Should not throw any exception
	}

	/**
	 * @test
	 */
	public function sendMaxi3() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('isOk'));
		$ResponseMock
			->expects($this->once())
			->method('isOk')
			->will($this->returnValue(false));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);

		// Exactly 160 characters
		$text = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At v';
		$msg = new SmsMessage($text, '00491234', '00491111');
		$service->send($msg); // Should not throw any exception
	}

	/**
	 * @test
	 */
	public function sendMaxi2() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('isOk'));
		$ResponseMock
			->expects($this->once())
			->method('isOk')
			->will($this->returnValue(false));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);

		// 591 characters = 4 SMS
		$text = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
		$msg = new SmsMessage($text, '00491234', '00491111');
		$service->send($msg); // Should not throw any exception
	}

	/**
	 * @test
	 */
	public function sendMaxi1() {

		$this->SmskaufenGateway = new SmskaufenGateway(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 13
			));

		$service = new SmsService($this->SmskaufenGateway);

		// 591 characters = 4 SMS
		$text = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
		$msg = new SmsMessage($text, '00491234', '00491111');
		$this->setExpectedException('Xi\Sms\SmsException');
		$service->send($msg);
	}

	/**
	 * @test
	 */
	public function sendFail2() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('getContent', 'getStatusCode'));
		$ResponseMock
			->expects($this->once())
			->method('getContent')
			->will($this->returnValue('121'));
		$ResponseMock
			->expects($this->once())
			->method('getStatusCode')
			->will($this->returnValue(200));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);
		$msg = new SmsMessage('Hi', '00491234', '00491111');
		$success = $service->send($msg);
		$this->assertFalse($success);
	}

	/**
	 * @test
	 */
	public function sendFail1() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('getStatusCode'));
		$ResponseMock
			->expects($this->once())
			->method('getStatusCode')
			->will($this->returnValue(404));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);
		$msg = new SmsMessage('Hi', '00491234', '00491111');
		$success = $service->send($msg);
		$this->assertFalse($success);
	}

	/**
	 * @test
	 */
	public function sendSuccess() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('getContent', 'getStatusCode'));
		$ResponseMock
			->expects($this->once())
			->method('getContent')
			->will($this->returnValue('100'));
		$ResponseMock
			->expects($this->once())
			->method('getStatusCode')
			->will($this->returnValue(200));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);
		$msg = new SmsMessage('Hi', '00491234', '00491111');
		$success = $service->send($msg);
		$this->assertTrue($success);
	}

	/**
	 * @test
	 */
	public function urlMassDispatch() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('getContent', 'isOk'));
		$ResponseMock
			->expects($this->once())
			->method('getContent')
			->will($this->returnValue('100'));
		$ResponseMock
			->expects($this->once())
			->method('isOk')
			->will($this->returnValue(true));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->with(
				$this->callback(function($actual) {
					$url = parse_url($actual);
					parse_str($url['query'], $query);
					return
						$url['scheme'] === 'http' &&
						$url['host'] === 'www.smskaufen.com' &&
						$url['path'] === '/sms/gateway/sms.php' &&
						$query['id'] === 'XXX' &&
						$query['pw'] === 'YYY' &&
						$query['type'] == 4 &&
						$query['text'] === 'Hi' &&
						$query['empfaenger'] === '00491111;015111111;0170111111' &&
						$query['absender'] === '00491234' &&
						$query['massen'] == 1 &&
						strtotime($query['termin']) < time();
				}),
				$this->isType('array')

			)
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'https' => false,
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);
		$msg = new SmsMessage('Hi', '00491234', array('00491111', '015111111', '0170111111'));
		$service->send($msg);
	}

	/**
	 * @test
	 */
	public function urlNormalDispatch() {

		$ResponseMock = $this->getMock('Buzz\Message\Response', array('getContent', 'isOk'));
		$ResponseMock
			->expects($this->once())
			->method('getContent')
			->will($this->returnValue('100'));
		$ResponseMock
			->expects($this->once())
			->method('isOk')
			->will($this->returnValue(true));

		$ClientMock = $this->getMock('Browser', array('get'));
		$ClientMock
			->expects($this->once())
			->method('get')
			->with(
				$this->callback(function($actual) {
					$url = parse_url($actual);
					parse_str($url['query'], $query);
					return
						$url['scheme'] === 'http' &&
						$url['host'] === 'www.smskaufen.com' &&
						$url['path'] === '/sms/gateway/sms.php' &&
						$query['id'] === 'XXX' &&
						$query['pw'] === 'YYY' &&
						$query['type'] == 4 &&
						$query['text'] === 'Hi' &&
						$query['empfaenger'] === '00491111' &&
						$query['absender'] === '00491234';
				}),
				$this->isType('array')
			)
			->will($this->returnValue($ResponseMock));

		$this->SmskaufenGateway = $this->getMock('Xi\Sms\Gateway\SmskaufenGateway', array('getClient'), array(array(
				'username' => 'XXX',
				'password' => 'YYY',
				'https' => false,
				'gateway' => 4
			)));
		$this->SmskaufenGateway
			->expects($this->once())
			->method('getClient')
			->will($this->returnValue($ClientMock));

		$service = new SmsService($this->SmskaufenGateway);
		$msg = new SmsMessage('Hi', '00491234', '00491111');
		$service->send($msg);
	}
}