<?php

namespace app\models;

use Yii;
use \app\models\base\Cuentas as BaseCuentas;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cuentas".
 */
class Cuentas extends BaseCuentas
{

public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
               'bedezign\yii2\audit\AuditTrailBehavior'
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
