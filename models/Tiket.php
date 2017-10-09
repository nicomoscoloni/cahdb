<?php

namespace app\models;

use Yii;
use \app\models\base\Tiket as BaseTiket;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tiket".
 */
class Tiket extends BaseTiket
{
 
    public $cuentapagadora;
    public $montoabonado;
    public $pagototal;
    public $cantidadservicios;
    public $importeservicios;
     

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
                [['cuentapagadora','montoabonado','pagototal', 'importeservicios'],'required'],
                [['montoabonado'], 'number'],
                [['fecha_tiket'], 'date', 'format' => 'php:Y-m-d'],                               
                [['xfecha_tiket'], 'date', 'format' => 'php:d-m-Y', 'message'=>'Ingrese una Fecha Valida'],                              
                [['xfecha_tiket','detalles'],'required'],
                [['fecha_tiket'],'rulesFechaPagoHabilitado'],
                [['importe'],'rulesControlImportesAbonado'],
                [['cantidadservicios'],'rulesControlCantidadServicios'],
             ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
             parent::rules(),
             [             
              'cuentapagadora' => 'Cuenta/Caja',
              'id_formapago' => 'Forma Pago',
              'xfecha_tiket' => 'Fecha Tiket', 
              'pagototal'=>'Abona/Liquida Total',
              'importeservicios'=>'Importe Servicios',
             ]
        );
    }
    
    public function getXfecha_tiket()
    {
        if (!empty($this->fecha_tiket) && $valor = Fecha::convertirFecha($this->fecha_tiket,"Y-m-d","d-m-Y"))
        {
            return $valor;
        } else {
            return $this->fecha_tiket;
        }
    }

    public function setXfecha_tiket($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d"))
        {
            $this->fecha_tiket = $valor;            
        }else{
            $this->fecha_tiket = $value;            
        }
    }
    
    
    
    public function rulesFechaPagoHabilitado() {
        if(Fecha::esFechaMayor(date('Y-m-d'), $this->fecha_tiket)){
            $this->addError('xfecha_tiket', 'La fecha del Tiket debe ser menor a la fecha Actual.');
        }  
       
    }
    
    public function rulesControlImportesAbonado() {
        if ($this->detalles=='COBRO SERVICIOS'){ 
            if (($this->pagototal=='1') && ($this->montoabonado != $this->importeservicios)){
                $this->addError('importeservicios', 'El Pago Total implica que debe abonarel mismo monto del importe del Tiket.');
                $this->addError('montoabonado', 'El Pago Total implica que debe abonarel mismo monto del importe del Tiket.');
            }
            if (($this->pagototal=='0') && ($this->montoabonado > $this->importeservicios)){
                $this->addError('montoabonado', 'El Monto Abonado Debe ser menor al Importe sel Servicio.');
            }
            if($this->cantidadservicios>1 && $this->montoabonado != $this->importeservicios)
                $this->addError('importeservicios', 'El Pago Parcial solo esta habilitado al pago de un unico servicio.');
        }
    }
    
    public function rulesControlCantidadServicios() {
        if (($this->detalles=='COBRO SERVICIOS') && ($this->cantidadservicios <= 0)){
            $this->addError('cantidadservicios', 'Debe Seleccionar al menos un servicio aABONAR.');            
        }
    }
}
