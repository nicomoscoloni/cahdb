<?php

namespace app\models;

use Yii;
use \app\models\base\ConvenioPago as BaseConvenioPago;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "convenio_pago".
 */
class ConvenioPago extends BaseConvenioPago
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
                ['fecha_alta', 'date', 'format' => 'php:Y-m-d'],
                ['xfecha_alta', 'date', 'format' => 'php:d-m-Y', 'message'=>'Ingrese una Fecha Valida'],
                ['xfecha_alta','required','message'=>'Ingrese una Fecha Valida.'] 
             ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                'xfecha_alta' => 'Fecha.Alta',  
                'id'=>'Nro'
            ]
        );
    }
    
    /**************************************************************/
    /**************************************************************/
    public function getXfecha_alta()
    {
        if (!empty($this->fecha_alta) && $valor = Fecha::convertirFecha($this->fecha_alta,"Y-m-d","d-m-Y"))
        {
            return $valor;
        } else {
            return $this->fecha_alta;
        }
    }

    public function setXfecha_alta($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d"))
        {
            $this->fecha_alta = $valor;
        }else{
            $this->fecha_alta = $value;
        }
    }  

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiFamilia()
    {
        return $this->hasOne(\app\models\GrupoFamiliar::className(), ['id' => 'id_familia']);
    }    

    /**************************************************************/
    /**************************************************************/
    /**************************************************************/
    /**************************************************************/
    
    public function getCantCuotas(){
        if(!empty($this->id)){
         $convenio = $this->id;             

         $cuotas = CuotaConvenioPago::find()->where("id_conveniopago = ".$convenio)->all();

         return "<span class='label label-success'>". count($cuotas)." </span>";
        }else
            return "";            
    }
    
    public function getCuotasPendientes(){
        if(!empty($this->id)){
            $convenio = $this->id;     

            $cuotasVencidas = CuotaConvenioPago::find()->where("estado='A' and id_conveniopago = ".$convenio)->all();
            if(empty($cuotasVencidas)){
                 return "<span class='label label-warning'>AL DIA</span>";
            }else{
                return "<span class='label label-warning'>". count($cuotasVencidas)." A PAGAR </span>"; 
            }          
        }else
            return "";            
    } 
}
