<?php

namespace app\models;

use Yii;
use \app\models\base\FormaPago as BaseFormaPago;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "forma_pago".
 */
class FormaPago extends BaseFormaPago
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
    
    public static function getFormasPago(){
        $dropciones = FormaPago::find()->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 'nombre');
    }     
}
