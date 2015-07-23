Xi SMS
=======

SMS library for PHP 5.3+.

Abstracts away the gateways. Just send'n go!

Pull requests are welcome! I'm not very actively maintaining this, though, and am the
worst possible maintainer anyways, so sorry if your pull requests take their time :(

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
