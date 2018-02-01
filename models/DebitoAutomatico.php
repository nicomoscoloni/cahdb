<?php

namespace app\models;

use Yii;
use \app\models\base\DebitoAutomatico as BaseDebitoAutomatico;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "debito_automatico".
 */
class DebitoAutomatico extends BaseDebitoAutomatico
{
    
    public $archivoentrante;
    
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
                [['fecha_creacion', 'inicio_periodo', 'fin_periodo'], 'date', 'format' => 'php:Y-m-d'],
                [['xfecha_debito','xfin_periodo','xinicio_periodo'], 'date', 'format' => 'php:d-m-Y','message'=>'Ingrese una Fecha Valida'],
                [['xfecha_debito','xinicio_periodo','xfin_periodo'],'required'],
                [['xinicio_periodo'], 'rulesPeriodoValido'], 
                [['archivoentrante'], 'file', 'skipOnEmpty' => true, 'extensions' => 'txt'],
             ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                'id' => 'Nro',
                'xfecha_creacion' => 'Fecha Creacion',
                'xinicio_periodo' => 'Inicio Periodo',
                'xfin_periodo' => 'Fin Periodo', 
                'xfecha_debito' => 'Fecha Vencimiento Debito', 
                'fecha_debito' => 'Fecha Vencimiento Debito',
            ]
        );
    } 
    
    /**************************************************************/
    /**************************************************************/
    
    public function getXfecha_debito()
    {
        if (!empty($this->fecha_debito) && $valor = Fecha::convertirFecha($this->fecha_debito,"Y-m-d","d-m-Y"))
        {
            return $valor;
        } else {
            return $this->fecha_debito;
        }
    }
    

    public function setXfecha_debito($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d"))
        {
            $this->fecha_debito = $valor;
        }else{
            $this->fecha_debito = $value;
        }
    }

    public function getXinicio_periodo()
    {
        if (!empty($this->inicio_periodo) && $valor = Fecha::convertirFecha($this->inicio_periodo,"Y-m-d","d-m-Y"))
        {
            return $valor;
        } else {
            return $this->inicio_periodo;
        }
    }

    public function setXinicio_periodo($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d"))
        {
            $this->inicio_periodo = $valor;
        }else{
            $this->inicio_periodo = $value;
        }
    }

    public function getXfin_periodo()
    {
        if (!empty($this->fin_periodo) && $valor = Fecha::convertirFecha($this->fin_periodo,"Y-m-d","d-m-Y"))
        {
            return $valor;
        } else {
            return $this->fin_periodo;
        }
    }

    public function setXfin_periodo($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d"))
        {
            $this->fin_periodo = $valor;
        }else{
            $this->fin_periodo = $value;
        }
    }
    
    public function getSaldoEntrante(){
        return "MODIFICAR";
    }
    
    public function getSaldoEnviado(){
        return ServicioDebitoAutomatico::find()->joinWith('idServicio s')
                ->where('id_debitoautomatico='.$this->id)->sum('(s.importe_servicio - s.importe_descuento - s.importe_abonado)');
    }
    
    public function getProcesado(){
        if($this->procesado=='0')
            return "<span class='label bg-red'> NO </span>";
        else
           return "<span class='bg bg-blue'> SI </span>"; 
    }
    
    public function getPeriodoBarrido(){
        
            return "<span class='label bg-red'>". $this->inicio_periodo ." ". $this->fin_periodo."</span>";
        
           
    }
    
    
    public function rulesPeriodoValido(){
       if(Fecha::esFechaMayor($this->fin_periodo, $this->inicio_periodo)){
        $this->addError('xinicio_periodo', 'Compruebe las fechas del Periodo.');
       }
       
    }
}
