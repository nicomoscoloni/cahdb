<?php

namespace app\models;

use Yii;
use \app\models\base\ServicioAlumno as BaseServicioAlumno;
use yii\helpers\ArrayHelper;

use \app\controllers\ConfController;

/**
 * This is the model class for table "servicio_alumno".
 */
class ServicioAlumno extends BaseServicioAlumno
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
  
        return $this->hasOne(\app\models\ServicioOfrecido::className(), ['id' => 'id_servicio']);
    }
    
    public function getDatosMiAlumno(){
        return $this->miAlumno->miPersona->nro_documento . " - " .$this->miAlumno->miPersona->apellido ." ".$this->miAlumno->miPersona->nombre;
    }    
    
    public function getDatosMiServicio(){
        return "(". $this->servicio->tiposervicio->descripcion. ") ". $this->servicio->nombre;
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
            case ConfController::estadoSA_ABIERTA: $estado='<span class="label label-sa-abierto"> Adeuda</span>'; break;
            case ConfController::estadoSA_ABONADA: $estado='<span class="label label-sa-abonado"> Abonada</span>'; break;
            case ConfController::estadoSA_EN_CONVENIOPAGO: $estado='<span class="label label-warning">Convenio Pago</span>'; break;
            
            case ConfController::estadoSA_EN_DEBITOAUTOMATICO: $estado='<span class="label label-danger">Débito Automático</span>'; break;
            case ConfController::estadoSA_ABONADA_EN_DEBITOAUTOMATICO: $estado='<span class="label label-sa-abonado">Abonada Deb.Automático</span>'; break;
            case ConfController::estadoSA_ABONADA_EN_CONVENIOPAGO: $estado='<span class="label label-sa-abonado">Abonada Convenio Pago</span>'; break;
        }
        return $estado;
        
    }
    
    public function getDetalleEstadoExcel(){
        switch ($this->estado){
            case ConfController::estadoSA_ABIERTA: $estado='Adeuda'; break;
            case ConfController::estadoSA_ABONADA: $estado='Liquidado'; break;
            case ConfController::estadoSA_EN_CONVENIOPAGO: $estado='En convenio pago'; break;
            
            case ConfController::estadoSA_EN_DEBITOAUTOMATICO: $estado='Enviada Debito Automatico'; break;
            case ConfController::estadoSA_ABONADA_EN_DEBITOAUTOMATICO: $estado='Abonada por Debito Automatico'; break;
            case ConfController::estadoSA_ABONADA_EN_CONVENIOPAGO: $estado='Abonada en Convenio de Pago'; break;
        }
        return $estado;
        
    }
    
    public static function getDetalleDatos($idServicio){
        $servicio = self::findOne($idServicio);
        return "(". $servicio->servicio->tiposervicio->descripcion. ") ". $servicio->servicio->nombre;    
        
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
                'pageSize' => 3,
            ],
        ]);
        
        $query->andFilterWhere([
            'a.id_grupofamiliar' => $cliente]);        
            $query->andFilterWhere(['=', 'estado', ConfController::estadoSA_ABIERTA]);

        return $dataProvider;
    }
    
    
    
    
}
