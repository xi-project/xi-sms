#!/usr/bin/env php
<?php

use Xi\Sms\SmsService;
use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\MessageBirdGateway;
use Xi\Sms\Event\Events;
use Xi\Sms\Event\SmsMessageEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

if (!@include __DIR__ . '/../vendor/autoload.php') {
    die("You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install --dev
");
}

gc_enable();

if (count($argv) != 3 || in_array('-h', $argv) || in_array('--argv', $argv)) {
    echo "Usage: {$argv[0]} from to\n";
    echo "Then input the text to send and press Ctrl+D\n";
    exit;
}

$from = $argv[1];
$to = $argv[2];

$ed = new EventDispatcher();

$gw = null;
$gw = new MessageBirdGateway('');
if (!$gw) {
    throw new LogicException('Configure gateway');
}

$service = new SmsService($gw, $ed);

$ed->addListener(
    Events::SEND,
    function (SmsMessageEvent $event) {
        $msg = $event->getMessage();
        echo "Message sent.\n";
        echo "From: " . $msg->getFrom() . "\n";
        echo "To: " . print_r($msg->getTo(), true) . "\n";
        echo "Body:\n" . $msg->getBody() . "\n\n";
    }
);

$body = stream_get_contents(STDIN);

$msg = new SmsMessage($body, $from, $to);

echo "Sending...\n";
$service->send($msg);
