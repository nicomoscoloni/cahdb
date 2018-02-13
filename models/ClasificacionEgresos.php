<?php

namespace app\models;

use Yii;
use \app\models\base\ClasificacionEgresos as BaseClasificacionEgresos;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "clasificacion_egresos".
 */
class ClasificacionEgresos extends BaseClasificacionEgresos
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
    /***********************************************************/
    /***********************************************************/
    public static function getClasificacionEgresos(){
        $dropciones = ClasificacionEgresos::find()->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 'descripcion');
    }      
}
