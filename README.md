# yii2-push-notification
Yii2 component, push notification for mobile devices (based on duccio/apns-php)

> **Note:** So far has only ios, but plans to expand the recipients

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require igancev/yii2-push-notification
```

or add

```
"igancev/yii2-push-notification": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, add component in config:

```php
$config = [
...
    'push' => [
        'class' => 'igancev\push\Push',
        'params' => [
            'ios' => [
                'class' => 'igancev\push\Ios', // must be an instance of igancev\push\RecipientTypeInterface, not required
                'environment' => ApnsPHP_Abstract::ENVIRONMENT_SANDBOX, // or ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION
                'apnsPem' => '@app/config/cert/iosDev.pem', // how generate .pem certificate read next
                'providerCertificatePassphrase' => '1234', // how get read next
                'logger' => 'igancev\push\FakeLogger', // must be an instance of \ApnsPHP_Log_Interface, not required
                //'rootCertificationAuthorityFile' => 'entrust_root_certification_authority.pem', //present by default, not required
            ],
        ],
    ],
...
];
```

Send notifications:

```php
use igancev\push\Message;
use igancev\push\Push;

/** @var Push $push */
$push = \Yii::$app->push;

// create message
/** @var Message $messageOne */
$messageOne = $push->createMessage();
$messageOne->setText('hello push 1!');
$messageOne->setDeviceTokens([
    '6E0E452B470B1642CBA124EFE0D04B38FDB1BB6F2AFDE0417C45A3F7B2C3CCDD',
    '6E0E452B470B1642CBA124EFE0D04B38FDB1BB6F2AFDE0417C45A3F7B2C3CCDE',
]);
$messageOne->addDeviceToken('6E0E452B470B1642CBA124EFE0D04B38FDB1BB6F2AFDE0417C45A3F7B2C3CCDF');
$messageOne->addCustomProperty('key', ['foo' => 'bar']);
$messageOne->setBadge(1);
$messageOne->setCustomIdentifier('Message-One');
$messageOne->setExpiry(30);
$messageOne->setSound('default');

// or another way
$messageTwo = new Message([
    'text' => 'hello push 1!',
    'deviceTokens' => [
        '6E0E452B470B1642CBA124EFE0D04B38FDB1BB6F2AFDE0417C45A3F7B2C3CCDD',
        '6E0E452B470B1642CBA124EFE0D04B38FDB1BB6F2AFDE0417C45A3F7B2C3CCDE',
    ],
    'customProperties' => [
        'key1' => ['foo' => 'bar'],
        'key2' => 'value'
    ],
    'badge' => 1,
    'customIdentifier' => 'Message-Two',
    'expiry' => 30,
    'sound' => 'default',
]);

// adding messages to queue
$push->ios->addMessage($messageOne);
$push->ios->addMessage($messageTwo);

// sending
if(!$push->ios->send()) {
    $errors = $push->ios->getErrors();
    // for example deleting expires device tokens
}
```

Or send notifications of all recipient types

```php
$push->sendAll();
$errors = $push->getErrors();
```

Generate .pem certificate for ios
-----

We have:

- file cert1.cer
- file cert2.p12
- password string (in example 1234)
        
Output:

- file ios.pem
- providerCertificatePassphrase (PEM pass phrase, in example 7777)

```
openssl x509 -in cert1.cer -inform der -out betweenCert.pem
openssl pkcs12 -nocerts -out betweenCertKey.pem -in cert2.p12
Enter Import Password: 1234
Enter PEM pass phrase: 7777
cat betweenCert.pem betweenCertKey.pem > ios.pem
```
