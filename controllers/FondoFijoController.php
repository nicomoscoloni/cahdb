<?php

namespace app\controllers;

use Yii;
use app\models\FondoFijo;
use app\models\search\FondoFijoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FondoFijoController implements the CRUD actions for FondoFijo model.
 */
class FondoFijoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [     
                        'actions' => ['listado','cargar-egreso','eliminar-egreso','actualizar-egreso'],
                        'allow' => true,
                        //'roles' => ['listarFondosFijos'],
                    ],
                    [     
                        'actions' => ['alta','actualizar'],
                        'allow' => true,
                        //'roles' => ['cargarFondoFijo'],
                    ],
                    [     
                        'actions' => ['delete',],
                        'allow' => true,
                        //'roles' => ['eliminarEstablecimiento'],
                    ],
                    [     
                        'actions' => ['view','mis-alumnos'],
                        'allow' => true,
                        //'roles' => ['visualizarEstablecimiento'],
                    ],
                    [     
                        'actions' => ['nuevo-servicio','servicios-division','quitar-servicio-division','get-servicios','asignar-servicio-division','mis-servicios-ofrecidos'],
                        'allow' => true,
                        'roles' => ['gestionarServiciosEstablecimiento'],
                    ],
                    [     
                        'actions' => ['cargar-division','actualizar-division','eliminar-division','mis-divisiones-escolares'],
                        'allow' => true,
                        'roles' => ['gestionarDivisionesEscolares'],
                    ],
                    [     
                        'actions' => ['drop-mis-divisionesescolares'],
                        'allow' => true,
                        //'roles' => ['filtrarDivisionesxEstablecimiento'],
                    ],                    
                ],
                'denyCallback' => function($rule, $action){                    
                    return $this->redirect(['site/index']);         
                } 
            ],  
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /************************************************************************/
    /************************************************************************/
    /**
     * Lists all FondoFijo models.
     * @return mixed
     */
    public function actionListado()
    {
        try{
            $searchModel = new FondoFijoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);   
            
        }catch (\Exception $e) { 
            Yii::error('Fondo Fijo '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }

    /************************************************************************/
    /************************************************************************/
    /**
     * Displays a single FondoFijo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        try{
            $modelFondoFijo = $this->findModel($id);
            
            $searchEgresos = new \app\models\search\EgresoFondoFijoSearch();
            $dataProviderEgresos = $searchEgresos->search(Yii::$app->request->queryParams);
        }catch (\Exception $e) { 
            Yii::error('View Fondo Fijo '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        
        return $this->render('view', [
            'model' => $modelFondoFijo,
            'searchEgresos' => $searchEgresos,
            'dataProviderEgresos' => $dataProviderEgresos,
        ]);
    }
    
    /************************************************************************/
    /************************************************************************/
    /**
     * Creates a new FondoFijo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAlta()
    {
        try{
            $transaction = Yii::$app->db->beginTransaction();    
            $model = new FondoFijo();  
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }catch (\Exception $e) { 
            Yii::error('Alta Fondo Fijo '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FondoFijo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActualizar($id)
    {
        try{
            $transaction = Yii::$app->db->beginTransaction();  
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }catch (\Exception $e) { 
            Yii::error('Actualizar Fondo Fijo '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    
    /************************************************************************/
    /************************************************************************/
    /**
     * Deletes an existing FondoFijo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FondoFijo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FondoFijo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FondoFijo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /************************************************************************/
    /************************************************************************/
    /**
     * Updates an existing FondoFijo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCargarEgreso($id_fondofijo)
    {
        try{
            $transaction = Yii::$app->db->beginTransaction();  
            
            $modelFondoFijo = $this->findModel($id_fondofijo);
            
            $modelEgreso = new \app\models\EgresoFondoFijo();
            $modelEgreso->id_fondofijo = $modelFondoFijo->id;
            $modelEgreso->topecompra_fondofijo = $modelFondoFijo->tope_compra;
            $modelEgreso->montoactual_fondofijo = $modelFondoFijo->monto_actual;
            $modelEgreso->rendido = '0';
            
            if ($modelEgreso->load(Yii::$app->request->post()) && $modelEgreso->save()) {
                $modelFondoFijo->monto_actual -= $modelEgreso->importe;
                if($modelFondoFijo->save()){
                    Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $modelFondoFijo->id]);    
                }
            }
        }catch (\Exception $e) { 
            Yii::error('Alta EgresoFondoFijo '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('cargarEgreso', [
            'model' => $modelEgreso,
        ]);
    }
    
    public function actionActualizarEgreso()
    {
        $id_egreso =  Yii::$app->request->get('id_egreso'); ;
        $modelEgreso = \app\models\EgresoFondoFijo::findOne($id_egreso);
        if(!$modelEgreso)
            throw new NotFoundHttpException('Servicio Ofrecido inexistente.');

        $modelFondoFijo = $this->findModel($modelEgreso->id_fondofijo);
        if(!$modelFondoFijo)
            throw new NotFoundHttpException('Servicio Ofrecido inexistente.');
        
        try{
            $transaction = Yii::$app->db->beginTransaction();  
            
            $modelEgreso->topecompra_fondofijo = $modelFondoFijo->tope_compra;
            $modelEgreso->montoactual_fondofijo = $modelFondoFijo->monto_actual;
            
            if ($modelEgreso->load(Yii::$app->request->post()) && $modelEgreso->save()) {
                $modelFondoFijo->monto_actual -= $modelEgreso->importe;
                if($modelFondoFijo->save()){
                    Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $modelFondoFijo->id]);    
                }
            }
        }catch (\Exception $e) { 
            Yii::error('Alta EgresoFondoFijo '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('cargarEgreso', [
            'model' => $modelEgreso,
        ]);
    }
    
    public function actionEliminarEgreso()
    {      
        $id_egreso =  Yii::$app->request->get('id_egreso'); ;
        $modelEgreso = \app\models\EgresoFondoFijo::findOne($id_egreso);
        if(!$modelEgreso)
            throw new NotFoundHttpException('Servicio Ofrecido inexistente.');

        $modelFondoFijo = $this->findModel($modelEgreso->id_fondofijo);
        if(!$modelFondoFijo)
            throw new NotFoundHttpException('Servicio Ofrecido inexistente.');
            
        $transaction = Yii::$app->db->beginTransaction();     
        try{
            $modelFondoFijo->monto_actual+= $modelEgreso->importe;
            
            if ($modelFondoFijo->save() &&  $modelEgreso->delete() ) {
                Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $modelFondoFijo->id]);    
                
            }
        }catch (\Exception $e) { 
            Yii::error('Elimimnar EgresoFondoFijo '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['view', 'id' => $modelFondoFijo->id]);
        }
    }
}
