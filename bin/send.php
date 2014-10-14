#!/usr/bin/env php
<?php

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

$ed = new \Symfony\Component\EventDispatcher\EventDispatcher();

$gw = null;
$gw = new \Xi\Sms\Gateway\MessageBirdGateway($ed, 'live_KRy5It4Db3u5enpx9vmAzHIEy');
if (!$gw) {
    throw new LogicException('Configure gateway');
}

$ed->addListener(
    'xi_sms.send',
    function (\Xi\Sms\Event\SmsMessageEvent $event) {
        $msg = $event->getMessage();
        echo "Message sent.\n";
        echo "From: " . $msg->getFrom() . "\n";
        echo "To: " . print_r($msg->getTo(), true) . "\n";
        echo "Body:\n" . $msg->getBody() . "\n\n";
    }
);

$body = stream_get_contents(STDIN);

$msg = new \Xi\Sms\SmsMessage($body, $from, $to);

echo "Sending...\n";
$gw->send($msg);
