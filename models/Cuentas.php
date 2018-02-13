<?php

namespace app\models;

use Yii;
use \app\models\base\Cuentas as BaseCuentas;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cuentas".
 */
class Cuentas extends BaseCuentas
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
    
    public static function getDropMisCuentas(){        
        $dropciones = Cuentas::find()->asArray()->all();
        return ArrayHelper::map($dropciones, 'id', 'nombre'); 
    } 
    
        /*     * *********************************************************** */
    /*     * *********************************************************** */
    /*
     * Metodo que se encarga de recibir una serie de parametros destinados
     * a acentar los movimientos realiozados por las operaciones de cobro de servicios
     * y las operaciones de sacado de plata
     */

    public static function AcentarMovimientosCaja($idcuenta, $tipooperacion, $importe, $detalleMovimiento, $fechaoperacion, $comentario = '', $tipopago, $id_servicio) {
        
        try {
            $modelCuenta = Cuentas::findOne((int)$idcuenta); 

            if (!empty($modelCuenta)) {
                $modelMovimientos = new MovimientoCuenta();
                
                if ($tipooperacion == 'INGRESO') {
                    $modelCuenta->saldo_actual += $importe;
                } else {
                    $modelCuenta->saldo_actual -= $importe;
                }

                $modelMovimientos->id_cuenta = $modelCuenta->id;
                $modelMovimientos->tipo_movimiento = $tipooperacion;
                $modelMovimientos->detalle_movimiento = $detalleMovimiento;
                $modelMovimientos->importe = $importe;                
                $modelMovimientos->fecha_realizacion =$fechaoperacion;               
                $modelMovimientos->comentario = $comentario;                
                
                $modelMovimientos->id_tipopago = $tipopago;                
                
                $modelMovimientos->id_hijo = $id_servicio;
                
                if ($modelCuenta->save() && $modelMovimientos->save())
                    return $modelMovimientos->id;
                else{        
                    $error = $modelMovimientos->getErrors();
                    return false;
                }
            }
            else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
