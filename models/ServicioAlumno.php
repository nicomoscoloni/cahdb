<?php

namespace app\models;

use Yii;
use \app\models\base\ServicioAlumno as BaseServicioAlumno;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "servicio_alumno".
 */
class ServicioAlumno extends BaseServicioAlumno
{
    const  SERVICIO_ABIERTO = 'A';
    const  SERVICIO_ABONADO = 'PA';    
    const  SERVICIO_EN_DEBITO_AUTOMATICO = 'DA';
    const  SERVICIO_ABONADO_DEBITO_AUTOMATICO = 'PA/DA';
    
    const  SERVICIO_EN_CONVENIO = 'CP';
    const  SERVICIO_ABONADO_EN_CONVENIO = 'CP';
    

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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiAlumno()
    {
        return $this->hasOne(\app\models\Alumno::className(), ['id' => 'id_alumno']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiServicio()
    {
  
        return $this->hasOne(\app\models\ServicioEstablecimiento::className(), ['id' => 'id_servicio']);
    }
    
    public function getDatosMiAlumno(){
        return $this->miAlumno->miPersona->nro_documento . " - " .$this->miAlumno->miPersona->apellido ." ".$this->miAlumno->miPersona->nombre;
    }    
    
    public function getDatosMiServicio(){
        return "(". $this->idServicio->idServicio->idTiposervicio->descripcion. ") ". $this->idServicio->idServicio->nombre;
    }
    
    
    public function getImporteAbonar(){
        $importe=$this->importe_servicio - $this->importe_descuento - $this->importe_abonado;
        return $importe;
    }
    
    public function getImporteRestante(){
        $importe=$this->importe_servicio - $this->importe_descuento - $this->importe_abonado;
        return $importe;
    }
    
    public function getDetalleEstado(){
        switch ($this->estado){
            case self::SERVICIO_ABIERTO: $estado='ABIERTO';                break;
            case self::SERVICIO_ABONADO: $estado='LIQUIDADO';break;
            case self::SERVICIO_EN_CONVENIO: $estado=' EN CONVENIO PAGO';break;
            case self::SERVICIO_ABONADO_EN_CONVENIO: $estado='LIQUIDADPO EN CONVENIO PAGO';break;
            case self::SERVICIO_EN_DEBITO_AUTOMATICO: $estado='EN DEBITO AUTOMATICO';break;
            case self::SERVICIO_ABONADO_DEBITO_AUTOMATICO: $estado='LIQUIDADPO EN DEBITO AUTOMATICO';break;
                
        }
        return $estado;
        
    }
    
    public static function getDetalleDatos($idServicio){
        $servicio = self::findOne($idServicio);
        return "(". $servicio->idServicio->idServicio->idTiposervicio->descripcion. ") ". $servicio->idServicio->idServicio->nombre;    
        
    }
    
    
    
    /************************************************************/
    /*
     * Funcion que se encarga de dado un identificador de cliente (familia);
     * devulelve todos los servicios asociados al mismo; que esten sin abonar o 
     *  sin asociar a ningun convenio de pago
     */
    public static function DevolverServiciosImpagosLibres($cliente){ 
        $query = \app\models\ServicioAlumno::find();        
        $query->joinWith('miAlumno a');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>'id desc'],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $query->andFilterWhere([
            'a.id_grupofamiliar' => $cliente]);        
            $query->andFilterWhere(['=', 'estado', 'A']);

        return $dataProvider;
    }
    
    
    
    
}
