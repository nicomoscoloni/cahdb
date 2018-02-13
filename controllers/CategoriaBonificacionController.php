<?php

namespace app\controllers;

use Yii;
use app\models\CategoriaBonificacion;
use app\models\search\CategoriaBonificacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriaBonificacionController implements the CRUD actions for CategoriaBonificacion model.
 */
class CategoriaBonificacionController extends Controller
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
                        'allow' => true,
                        'roles' => ['gestionarCategoriaDescuentos'],
                    ],
                ],
            ],  
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /****************************************************************/
    /**
     * Deletes an existing CategoriaBonificacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
             
                if ( $this->findModel($id)->delete() ){
                    $transaction->commit();
                    if (Yii::$app->request->isAjax){                    
                        Yii::$app->response->format = 'json';
                        return ['error' => '0', 'message' => Yii::$app->params['eliminacionCorrecta']];
                    }else{
                        Yii::$app->session->setFlash('ok',Yii::$app->params['eliminacionCorrecta']);
                        return $this->redirect(['index']);
                    }
                }
            
            
        }
        catch (\Exception $e){
            $transaction->rollBack();
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'message' =>  Yii::$app->params['errorExcepcion']];
            }else{
                Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
                return $this->redirect(['index']);
            }
        }
    }
    
    /*****************************************************************/
    /*****************************************************************/
    /**
     * Creates a new CategoriaBonificacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function  actionCreate($id=null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try{
            if(!empty($id))
                $model = $this->findModel($id);
            else
                $model = new CategoriaBonificacion();

            if ($model->load(Yii::$app->request->post())) {
                if($model->isNewRecord)
                    $mensaje = Yii::$app->params['cargaCorrecta'];
                else
                    $mensaje = Yii::$app->params['actualizacionCorrecta'];
                    
                if ($model->save()){                    
                    $transaction->commit();
                    if (Yii::$app->request->isAjax){
                        Yii::$app->response->format = 'json';
                        return ['carga' => '1', 'error' => '0', 'message' => $mensaje, 'id'=>$model->id];
                    }else{
                        Yii::$app->session->setFlash('ok',$mensaje);
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }else{
                    $transaction->rollBack();
                    if (Yii::$app->request->isAjax){
                        Yii::$app->response->format = 'json';
                        return ['carga' => '0', 'error' => '0', 'message' => Yii::$app->params['operacionFallida'], 
                                'vista' => $this->renderAjax('create', [
                                        'model' => $model,
                                ])];
                    }else{
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }
            }
            
            //renderizamos las vistas, formulario de carga
            if (Yii::$app->request->isAjax){
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }            
        }catch(\Exception $e){
            $transaction->rollBack();
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'message' => Yii::$app->params['errorExcepcion']];
            }else{
                Yii::$app->session->setFlash('error',Yii::$app->params['errorExcepcion']);
                
            }            
        }        
    } //fin createAjax

    
    /*****************************************************************/
    /*****************************************************************/
    /**
     * Lists all CategoriaBonificacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new CategoriaBonificacionSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    /*********************************************************************/
    /**
     * Displays a single TipoSexp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
 
    /**
     * Finds the CategoriaBonificacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoriaBonificacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoriaBonificacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}