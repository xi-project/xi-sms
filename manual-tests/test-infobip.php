#!/usr/bin/env php
<?php

require_once __DIR__ . '/common.php';

if (count($argv) != 4 || in_array('-h', $argv) || in_array('--argv', $argv)) {
    echo "Usage: {$argv[0]} user password recipient\n";
    echo "Then input the text to send and press Ctrl+D\n";
    exit;
}

$user = $argv[1];
$password = $argv[2];
$recipient = $argv[3];

$ed = new \Symfony\Component\EventDispatcher\EventDispatcher();
$gw = new \Xi\Sms\Gateway\InfobipGateway($ed, $user, $password);

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

$msg = new \Xi\Sms\SmsMessage($body, 'xi-sms test', $recipient);

echo "Sending...\n";
$gw->send($msg);
