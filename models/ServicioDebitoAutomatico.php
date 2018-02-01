<?php

namespace app\models;

use Yii;
use \app\models\base\ServicioDebitoAutomatico as BaseServicioDebitoAutomatico;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "servicio_debito_automatico".
 */
class ServicioDebitoAutomatico extends BaseServicioDebitoAutomatico
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
