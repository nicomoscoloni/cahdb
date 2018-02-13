<?php

namespace app\models;

use Yii;
use \app\models\base\FondoFijo as BaseFondoFijo;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fondo_fijo".
 */
class FondoFijo extends BaseFondoFijo
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
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'id_establecimiento' => 'Establecimiento',
            ]
        );
    }
}
