<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\widgets\buscadorServiciosAlumno;

use Yii;

class BuscadorServiciosAlumno extends \yii\bootstrap\Widget
{       
    public $searchModel;
    public $dataProvider; 
    public $buscador = true;
    public $filtrosgrilla = false;
    
    
    public function run()
    {      
        $searchModel = $this->searchModel;
        
        $filtro_estados=[
            'PA'=>'Abonada',
            'A'=>'Adeuda',
            'DA'=>'En Debito Automatico',
            'CP'=>'En Convenio Pago',
            'PA/CP'=>'Abonada Convenio Pago',
            'PA/DA'=>'Abonada Debito Automatico',            
        ];
        $filtro_establecimiento= \app\models\Establecimiento::getEstablecimientos();
        if(!empty($searchModel->establecimiento)){
            $filtro_divisiones = \app\models\DivisionEscolar::getDivisionesEstablecimiento($searchModel->establecimiento);
        }else{
            $filtro_divisiones=[];
        }
        echo $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $this->dataProvider,
            'buscador'=>$this->buscador,
            'filtrosgrilla'=>$this->filtrosgrilla,
            'filtro_estados'=>$filtro_estados,
            'filtro_establecimiento'=>$filtro_establecimiento,
            'filtro_divisiones'=>$filtro_divisiones
        ]);
    }
    
}
