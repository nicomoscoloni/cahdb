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
    
    const estadoSA_ABIERTA = 'A'; //abierta libre asignar 
    const estadoSA_ABONADA = 'PA'; //abonada
    const estadoSA_EN_DEBITOAUTOMATICO = 'DA';
    const estadoSA_EN_CONVENIOPAGO = 'CP';    
    const estadoSA_ABONADA_EN_DEBITOAUTOMATICO = 'PA/DA';
    const estadoSA_ABONADA_EN_CONVENIOPAGO = 'PA/CP';
    
    
    const idCajaTribunales = 1;
    const idCajaBelgrano = 2;
    const idCajaPatagonia = 3;
    
    const  SERVICIO_LIBRES = 'Ingreso Libres';
    const  SERVICIO_MATRICULA = 'Matricula';
    const  SERVICIO_SERVICIOS = 'Servicios';
    const  SERVICIO_CONVENIO_PAGO = 'Cuota Convenio Pago';
    
    const PAGO_EFECTIVO = 1;
    const PAGO_CHEQUE = 6;
    
    const CUENTA_COLEGIO = 1;
    const CUENTA_PATAGONIA = 2;
    
    
    
    
}