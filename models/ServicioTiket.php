<?php

namespace app\models;

use Yii;
use \app\models\base\ServicioTiket as BaseServicioTiket;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "servicio_tiket".
 */
class ServicioTiket extends BaseServicioTiket
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
    
    public function getMiDetalle(){
        if($this->tipo_servicio == \app\controllers\ConfController::SERVICIO_SERVICIOS){
            $modelServicioAlumno = ServicioAlumno::findOne($this->id_servicio);
            return $modelServicioAlumno->servicio->nombre . "  -  " . $modelServicioAlumno->servicio->tiposervicio->descripcion . "     $" .$modelServicioAlumno->importe_servicio;
        }else
        if($this->tipo_servicio == \app\controllers\ConfController::SERVICIO_CONVENIO_PAGO){
            $modelCuotaCP = CuotaConvenioPago::findOne($this->id_servicio);
            $modelConvenio = ConvenioPago::findOne($modelCuotaCP->id_conveniopago);
            
            return "Convenio Nº: ". $modelConvenio->id . " Cuota: Nº: ".$modelCuotaCP->nro_cuota . " ". $modelCuotaCP->importe_abonado;
        }
    }    
}
