<?php

namespace app\controllers;

class ConfController extends \yii\web\Controller
{
    const estadoCuota_ABIERTA = 'A'; //abierta libre asignar 
    const estadoCuota_ABONADA = 'PA'; //abonada
    const estadoCuota_EN_DEBITOAUTOMATICO = 'DA';
    const estadoCuota_EN_CONVENIOPAGO = 'CP';    
    const estadoCuota_ABONADA_EN_DEBITOAUTOMATICO = 'PA/DA';
    const estadoCuota_ABONADA_EN_CONVENIOPAGO = 'PA/CP';
    
    const idCajaTribunales = 1;
    const idCajaBelgrano = 2;
    const idCajaPatagonia = 3;
    
    const  SERVICIO_MATRICULA = 2;
    const  SERVICIO_CUOTA = 3;
    const  SERVICIO_CONVENIO = 5;
    
    const PAGO_EFECTIVO = 1;
    const PAGO_CHEQUE = 6;
    
    const CUENTA_PATAGONIA = 3;
    const CUENTA_TRIBUNALES = 1;
    const CUENTA_BELGRANO = 2;
    
    
    
    
}