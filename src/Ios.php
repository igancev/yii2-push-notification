<?php

namespace igancev\push;

use ApnsPHP_Message;
use ApnsPHP_Push;
use Yii;
use yii\base\Object;

class Ios extends Object implements RecipientTypeInterface
{
    public $apnsPem;
    public $providerCertificatePassphrase;
    public $rootCertificationAuthorityFile;
    public $environment;
    public $logger;
    public $errors = [];
    protected $messages = [];

    /**
     * @var ApnsPHP_Push
     */
    protected $apnsPhp;

    public function init()
    {
        $this->apnsPhp = new ApnsPHP_Push(
            $this->environment,
            Yii::getAlias($this->apnsPem)
        );

        $this->apnsPhp->setProviderCertificatePassphrase($this->providerCertificatePassphrase);

        if(!empty($this->rootCertificationAuthorityFile)) {
            $this->apnsPhp->setRootCertificationAuthority(Yii::getAlias($this->rootCertificationAuthorityFile));
        } else {
            $this->apnsPhp->setRootCertificationAuthority(__DIR__ . '/cert/entrust_root_certification_authority.pem');
        }

        if(!empty($this->logger)) {
            /** @var \ApnsPHP_Log_Interface $logger */
            $logger = Yii::createObject($this->logger);
            $this->apnsPhp->setLogger($logger);
        }
    }

    /**
     * @param Message $message
     */
    public function addMessage(Message $message)
    {
        $apnsMessage = new ApnsPHP_Message;

        if(!empty($message->deviceTokens)) {
            foreach ($message->deviceTokens as $deviceToken) {
                $apnsMessage->addRecipient($deviceToken);
            }
        }

        $apnsMessage->setCustomIdentifier(!empty($message->customIdentifier) ?
            $message->customIdentifier : 'Message-' . count($this->messages));

        if(!empty($message->badge)) {
            $apnsMessage->setBadge($message->badge);
        }

        $apnsMessage->setText($message->text);

        $apnsMessage->setSound(!empty($message->sound) ? $message->sound : 'default');
        $apnsMessage->setExpiry(!empty($message->expiry) ? $message->expiry : 30);

        if(!empty($message->customProperties)) {
            foreach ($message->customProperties as $key => $val) {
                $apnsMessage->setCustomProperty($key, $val);
            }
        }

        $this->messages[] = $apnsMessage;
    }

    public function send()
    {
        // Connect to the Apple Push Notification Service
        $this->apnsPhp->connect();

        /** @var ApnsPHP_Message $message */
        foreach ($this->messages as $message) {
            // Add the message to the message queue
            $this->apnsPhp->add($message);
        }

        // Send all messages in the message queue
        $this->apnsPhp->send();

        // Disconnect from the Apple Push Notification Service
        $this->apnsPhp->disconnect();

        $this->errors = $this->apnsPhp->getErrors();

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
