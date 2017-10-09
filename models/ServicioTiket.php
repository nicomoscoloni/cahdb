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
        if($this->id_tiposervicio == self::SERVICIO_CUOTA){
            $modelServicioAlumno = ServicioAlumno::findOne($this->id_servicio);
            return $modelServicioAlumno->idServicio->idServicio->nombre . "  -  " . $modelServicioAlumno->idServicio->idServicio->idTiposervicio->descripcion . "     $" .$modelServicioAlumno->importe_servicio;
        }else
        if($this->id_tiposervicio == self::SERVICIO_CONVENIO){
            return "CUOTA";
        }
    }    
}
