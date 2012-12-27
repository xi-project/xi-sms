<?php

/**
 * This file is part of the Xi SMS package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Sms\Gateway;

use Xi\Sms\SmsMessage;

/**
 * Mock gateway just stores the sent messages so they can be inspected at will.
 */
class MockGateway extends AbstractGateway
{
    /**
     * @var array
     */
    private $sentMessages = array();

    public function send(SmsMessage $message)
    {
        $this->sentMessages[] = $message;
    }

    /**
     * @return array
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }
}
