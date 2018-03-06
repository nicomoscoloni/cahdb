<?php

namespace app\models;

use Yii;
use \app\models\base\ServicioDebitoAutomatico as BaseServicioDebitoAutomatico;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "servicio_debito_automatico".
 */
class ServicioDebitoAutomatico extends BaseServicioDebitoAutomatico
{

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
                  # custom validation rules
             ]
        );
    }
    
    public function getDetalleMiServicio(){
        if($this->tiposervicio=='SERVICIOS'){
            $servicioAlumno = \app\models\ServicioAlumno::findOne($this->id_servicio);

            $alumno = \app\models\Alumno::findOne($servicioAlumno->id_alumno);
            return $servicioAlumno->datosMiServicio . " ". $servicioAlumno->importeAbonar;
       }else
        if($this->tiposervicio=='CUOTAS CONVENIO PAGO'){
            $cuotaConvenioPago = \app\models\CuotaConvenioPago::findOne($this->id_servicio);
            $convenioPago = \app\models\ConvenioPago::findOne($cuotaConvenioPago->id_conveniopago); 
            $familia = \app\models\GrupoFamiliar::findOne($convenioPago->id_familia);
            return "Convenio Pago Nº:".$convenioPago->id. " Nº cuota: ".$cuotaConvenioPago->id." -$".$cuotaConvenioPago->monto;
        }
    }
    
    public function getDetalleAlumno(){
        if($this->tiposervicio=='SERVICIOS'){
            $servicioAlumno = \app\models\ServicioAlumno::findOne($this->id_servicio);
            $alumno = \app\models\Alumno::findOne($servicioAlumno->id_alumno);
            return $alumno->persona->apellido . " ". $alumno->persona->nombre;
          }else
        if($this->tiposervicio=='CUOTAS CONVENIO PAGO'){
            $cuotaConvenioPago = \app\models\CuotaConvenioPago::findOne($this->id_servicio);
            $convenioPago = \app\models\ConvenioPago::findOne($cuotaConvenioPago->id_conveniopago); 
            $familia = \app\models\GrupoFamiliar::findOne($convenioPago->id_familia);
            return $familia->apellidos;
        }
    }
}
