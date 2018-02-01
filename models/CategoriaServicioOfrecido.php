<?php

namespace app\models;

use Yii;
use \app\models\base\CategoriaServicioOfrecido as BaseCategoriaServicioOfrecido;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categoria_servicio_ofrecido".
 */
class CategoriaServicioOfrecido extends BaseCategoriaServicioOfrecido
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
    
    public static function getTipoServicios(){
        $dropciones = CategoriaServicioOfrecido::find()->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 'descripcion');
    }  
}
