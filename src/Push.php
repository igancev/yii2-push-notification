<?php

namespace igancev\push;

use yii\base\Component;
use yii\helpers\ArrayHelper;

class Push extends Component
{
    /**
     * Config params
     * @var array
     */
    public $params;

    /**
     * @var Ios
     */
    public $ios = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        // ios
        if(isset($this->params['ios'])) {
            $this->ios = \Yii::createObject(ArrayHelper::merge([
                'class' => !empty($this->params['ios']['class']) ? $this->params['ios']['class'] : Ios::className(),
            ], $this->params['ios']));
        }
    }

    /**
     * Call method send() of all initialized recipient types
     * @return void
     */
    public function sendAll()
    {
        $recipientTypes = $this->getRecipientTypes();

        /** @var RecipientTypeInterface $recipientType */
        foreach ($recipientTypes as $recipientType) {
            $recipientType->send();
        }
    }

    /**
     * @param array $params
     * @return Message
     */
    public function createMessage(array $params)
    {
        return new Message($params);
    }

    /**
     * Get initialized recipient types
     * @return array
     */
    protected function getRecipientTypes()
    {
        $recipientTypes = [];

        $propNames = get_class_vars(self::className());

        foreach ($propNames as $propName => $val) {
            if(is_subclass_of($this->$propName, 'igancev\push\RecipientTypeInterface')) {
                $recipientTypes[] = $this->$propName;
            }
        }

        return $recipientTypes;
    }
}
