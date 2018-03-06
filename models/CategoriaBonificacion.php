<?php

namespace app\models;

use Yii;
use \app\models\base\CategoriaBonificacion as BaseCategoriaBonificacion;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categoria_bonificacion".
 */
class CategoriaBonificacion extends BaseCategoriaBonificacion
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
             parent::rules(),
             [
                 'tipobonificacion' => 'Tipo Bonificacion',
             ]
        );
    }
    
    public function getDetalleBonificacionesDrop(){
        $dropciones = CategoriaBonificacion::find()->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 
                function ($element) {
                    return $element['descripcion'] . '  (Monto: '. $element['valor'].'%)';
                });
        
    }    
    
    public function getDetalleBonificacionesActivasDrop(){
        $dropciones = CategoriaBonificacion::find()->where("activa = '1'")->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 
                function ($element) {
                    return $element['descripcion'] . '  (Monto: '. $element['valor'].'%)';
                });
        
    }  
    
    /***********************************************************/
    /***********************************************************/
    public static function getBonificaciones(){
        $dropciones = CategoriaBonificacion::find()->asArray()->all();
        return  ArrayHelper::map($dropciones, 'id', 'descripcion');
    }    
}
