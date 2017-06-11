<?php

namespace igancev\push;

use yii\base\Object;

class Message extends Object
{
    /**
     * @var array
     */
    public $deviceTokens = [];
    /**
     * @var string
     */
    public $customIdentifier;
    /**
     * @var string
     */
    public $badge;
    /**
     * @var string
     */
    public $text;
    /**
     * @var string
     */
    public $sound;
    /**
     * @var integer
     */
    public $expiry;
    /**
     * @var array
     */
    public $customProperties = [];

    /**
     * @param string $deviceToken
     * @return void
     */
    public function addDeviceToken($deviceToken)
    {
        $this->deviceTokens[] = $deviceToken;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addCustomProperty($key, $value)
    {
        $this->customProperties[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param integer $badge
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @param mixed $sound
     */
    public function setSound($sound = 'default')
    {
        $this->sound = $sound;
    }

    /**
     * @return mixed
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * @param integer $expiry
     */
    public function setExpiry($expiry)
    {
        $this->expiry = $expiry;
    }

    /**
     * @return array
     */
    public function getDeviceTokens()
    {
        return $this->deviceTokens;
    }

    /**
     * @param array $deviceTokens
     */
    public function setDeviceTokens(array $deviceTokens)
    {
        $this->deviceTokens = $deviceTokens;
    }

    /**
     * @return array
     */
    public function getCustomProperties()
    {
        return $this->customProperties;
    }

    /**
     * @param array $customProperties
     */
    public function setCustomProperties(array $customProperties)
    {
        $this->customProperties = $customProperties;
    }

    /**
     * @return mixed
     */
    public function getCustomIdentifier()
    {
        return $this->customIdentifier;
    }

    /**
     * @param mixed $customIdentifier
     */
    public function setCustomIdentifier($customIdentifier)
    {
        $this->customIdentifier = $customIdentifier;
    }
}
