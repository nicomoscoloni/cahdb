<?php

namespace app\models;

use Yii;
use \app\models\base\DebitoAutomatico as BaseDebitoAutomatico;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "debito_automatico".
 */
class DebitoAutomatico extends BaseDebitoAutomatico
{

public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
             parent::rules(),
             [
                  # custom validation rules
             ]
        );
    }
}
