<?php

namespace app\models;

use Yii;
use \app\models\base\ServicioEstablecimiento as BaseServicioEstablecimiento;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "servicio_establecimiento".
 */
class ServicioEstablecimiento extends BaseServicioEstablecimiento
{
    public $divisiones;
    public $establecimiento;

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
               ['divisiones','safe'],
               ['establecimiento','safe']# custom validation rules
             ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                
                'id_servicio' => 'Servicio',                
            ]
        );
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiDivisionescolar()
    {
        return $this->hasOne(\app\models\DivisionEscolar::className(), ['id' => 'id_divisionescolar']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiServicio()
    {
        return $this->hasOne(\app\models\ServicioOfrecido::className(), ['id' => 'id_servicio']);
    }
    
    public static function getServiciosxEstablecimiento($idEst){
        $query = (new \yii\db\Query());
        $query->from('servicio_establecimiento');
        $query->join('INNER JOIN','division_escolar d','d.id =  servicio_establecimiento.id_divisionescolar');
        $query->join('INNER JOIN','servicio_ofrecido s','s.id =  servicio_establecimiento.id_servicio');        
        $query->select(['DISTINCT(s.id) as id','s.nombre as nombre']);
        $query->where('d.id_establecimiento='.$idEst);        
        $dropciones=  $query->createCommand()->queryAll();        
        
        $opciones = []; 
        
        foreach($dropciones as $drop){
            $opciones[$drop['id']] = $drop['nombre'];
        };
        return $opciones;
        
    }

}
