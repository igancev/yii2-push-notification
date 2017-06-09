<?php

namespace igancev\push;

use yii\base\Component;
use yii\helpers\ArrayHelper;

class Push extends Component
{
    public $params;

    /**
     * @var Ios
     */
    public $ios = null;

    public function init()
    {
        // ios
        if(isset($this->params['ios'])) {
            $this->ios = \Yii::createObject(ArrayHelper::merge([
                'class' => Ios::className(),
            ], $this->params['ios']));
        }
    }
}
