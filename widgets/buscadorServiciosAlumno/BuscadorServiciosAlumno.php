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
        $filtro_estados=[
            'A'=>'Adeuda',
            'DA'=>'EN Deb.Aut',
            'CP'=>'En Conv.Pago',
            'PA/CP'=>'Abonada Conv.Pago',
            'PA/DA'=>'Abonada Deb.Aut',
            
        ];
        echo $this->render('index', [
            'searchModel' => $this->searchModel,
            'dataProvider' => $this->dataProvider,
            'buscador'=>$this->buscador,
            'filtrosgrilla'=>$this->filtrosgrilla,
            'filtro_estados'=>$filtro_estados
        ]);
    }
    
}
