<?php

namespace app\controllers;

use Yii;
use app\models\ServicioAlumno;
use app\models\search\ServicioAlumnoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicioAlumnoController implements the CRUD actions for ServicioAlumno model.
 */
class ServicioAlumnoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /*******************************************************************/
    /******************** Reporte General *** **************************/            
    public function actionReporte()
    {
        $searchModel = new ServicioAlumnoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());

        return $this->render('reporte', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
 
}
