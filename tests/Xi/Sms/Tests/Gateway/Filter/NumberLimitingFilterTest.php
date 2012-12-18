<?php

namespace Svt\Bundle\MainBundle\Tests\Services\Sms\Gateway\Filter;

use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\Filter\NumberLimitingFilter;


class NumberLimitingFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Sms\Gateway\Filter\NumberLimitingFilter'));
        $this->assertContains(
            'Xi\Sms\Gateway\Filter\FilterInterface',
            class_implements('Xi\Sms\Gateway\Filter\NumberLimitingFilter')
        );
    }

    /**
     * @test
     */
    public function shouldAcceptWhenNoBlacklistsOrWhitelists()
    {
        $filter = new NumberLimitingFilter();
        $message = new SmsMessage('Body moving', 'BodyMover', '358503028030');
        $ret = $filter->accept($message);
        $this->assertTrue($ret);
    }


    /**
     * @test
     */
    public function shouldNotAcceptIfNoReceiversAreLeft()
    {
        $filter = new NumberLimitingFilter(array(), array('#^358503028030$#'));
        $message = new SmsMessage('Body moving', 'BodyMover', '358503028030');
        $ret = $filter->accept($message);
        $this->assertFalse($ret);
        $this->assertCount(0, $message->getTo());
    }

    /**
     * @test
     */
    public function onlyTheStrongestShouldRemainAfterWhitelistingAndBlacklisting()
    {
        $filter = new NumberLimitingFilter(
            array('#^358#'),
            array('#^3585030280(30|31)$#', '#666$#')
        );

        $message = new SmsMessage('Body moving', 'BodyMover', '358503028031');
        $message->addTo('358403028030');

        $message->addTo('359503028030');
        $message->addTo('358503028032');

        $message->addTo('3593028030');
        $message->addTo('563028030');

        $ret = $filter->accept($message);
        $this->assertTrue($ret);
        $this->assertCount(2, $message->getTo());
    }
}
