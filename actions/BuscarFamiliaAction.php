<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\actions;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\UserException;
use app\models;

use app\models\search\GrupoFamiliarSearch;
use app\widgets\buscadorfamilia\BuscadorFamilia;

/**
 * Acción reutilizable para la búsqueda de legajos
 *
 * @author mboisselier
 */
class BuscarFamiliaAction extends Action
{
    public function run()
    {       
        try{
            $searchModel = new GrupoFamiliarSearch();
            $dataProvider = $searchModel->searchBuscador(Yii::$app->request->getQueryParams());        
            return $this->controller->renderAjax('@app/actions/clean/views/clean', ['contenido'=> BuscadorFamilia::widget(['searchModel'=>$searchModel, 'dataProvider'=>$dataProvider])]);
        }catch (\Exception $e) { 
            Yii::error('BuscadorFamili run  '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            throw new \yii\web\HttpException(500,'No se puden mostrar las familias.');                        
        }
    }    
    
}
