<?php

namespace app\models;

use Yii;
use \app\models\base\EgresoFondoFijo as BaseEgresoFondoFijo;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "egresos_fondofijo".
 */
class EgresoFondoFijo extends BaseEgresoFondoFijo
{

    public $topecompra_fondofijo;
    public $montoactual_fondofijo;
    
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
              [['topecompra_fondofijo','montoactual_fondofijo'],'safe'],
              ['xfecha_compra', 'required', 'message'=>'Fecha Invalida.'],
              ['xfecha_compra', 'date', 'format' => 'php:d-m-Y','message'=>'Fecha Invalida.'],
              ['xfecha_compra', 'rulesFechaAperturaValida'],
              ['importe', 'rulesImporteValido'],     
             ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'xfecha_compra' => 'Fec.Compra',
            ]
        );
    }
    
    public function rulesFechaAperturaValida() {
        if(!empty($this->fecha_compra)){
            if(Fecha::esFechaMayor(date('Y-m-d'), $this->fecha_compra)){
                $this->addError('xfecha_compra', 'La fecha de compra debe ser menor a la fecha Actual.');
            }    
        }
    }  
    
    public function rulesImporteValido() {
        if($this->topecompra_fondofijo < $this->importe)
            $this->addError('importe', 'El importe debe ser menor que el toope de compra del fondo fijo. Tope Compra = '.$this->topecompra);
        if($this->montoactual_fondofijo < 0)
            $this->addError('importe', 'El importe es demasiado garnde; sin plata en elfondo fjo');
            
    }  
    
    /***********************************************************/
    /***********************************************************/
    public static function getEstablecimientos(){
        $dropciones = Establecimiento::find()->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 'nombre');
    }
    /***********************************************************/
    /***********************************************************/
    public function getXfecha_compra()
    {
        if (!empty($this->fecha_compra) && $valor = Fecha::convertirFecha($this->fecha_compra,"Y-m-d","d-m-Y"))
        {
            return $valor;
        } else
        {
            return $this->fecha_compra;
        }
    }

    public function setXfecha_compra($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d")) 
        {
            $this->fecha_compra = $valor;
        } else
        {
            $this->fecha_compra = $value;
        }
    }    
}
