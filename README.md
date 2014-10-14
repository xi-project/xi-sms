Xi SMS
=======

SMS library for PHP 5.3+.

Abstracts away the gateways. Just send'n go!

Pull requests are very welcome!

```php
<?php

use Xi\Sms\SmsService;
use Xi\Sms\SmsMessage;
use Xi\Sms\Gateway\MessageBirdGateway;

$gw = new MessageBirdGateway('my_apikey');
$service = new SmsService($gw);

$msg = new SmsMessage('message', 'sender', 'receiver_msisdn');
$service->send($msg);

```
