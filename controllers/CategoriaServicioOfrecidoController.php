<?php

namespace app\controllers;

use Yii;
use app\models\CategoriaServicioOfrecido;
use app\models\search\CategoriaServicioOfrecidoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriaServicioOfrecidoController implements the CRUD actions for CategoriaServicioOfrecido model.
 */
class CategoriaServicioOfrecidoController extends Controller
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
                        'roles' => ['gestionarCategoriaServicios'],
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
     * Deletes an existing CategoriaServicioOfrecido model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            if($id>5){  
                if ( $this->findModel($id)->delete() ){
                    $transaction->commit();
                    if (Yii::$app->request->isAjax){                    
                        Yii::$app->response->format = 'json';
                        return ['error' => '0', 'mensaje' => Yii::$app->params['eliminacionCorrecta']];
                    }else{
                        Yii::$app->session->setFlash('ok',Yii::$app->params['eliminacionCorrecta']);
                        return $this->redirect(['index']);
                    }
                }
            }else{
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'mensaje' => 'No se puede realizar la eliminación. El registro se encuentra bloqueado'];    
            }
        }
        catch (\Exception $e){
            Yii::error('Delete CategoriaServicioOfrecido'.$e);
            $transaction->rollBack();
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'mensaje' =>  Yii::$app->params['errorExcepcion']];
            }else{
                Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
                return $this->redirect(['index']);
            }
        }
    }
    
    /*****************************************************************/
    /*****************************************************************/
    /**
     * Creates a new CategoriaServicioOfrecido model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function  actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $mensaje = '';
        $id = Yii::$app->request->get('id');
        
        try{
            if(!empty($id)){
                if($id<0)
                    $mensaje = 'No se puede actualizar el registro, el mismo se encuentra bloqueado';    
                $model = $this->findModel($id);
            }
            else
                $model = new CategoriaServicioOfrecido();

            if ($model->load(Yii::$app->request->post())) {                
                ($model->isNewRecord)?$mensaje = Yii::$app->params['cargaCorrecta']:$mensaje = Yii::$app->params['actualizacionCorrecta'];
                    
                if ($model->save()){                    
                    $transaction->commit();
                    if (Yii::$app->request->isAjax){
                        Yii::$app->response->format = 'json';
                        return ['carga' => '1', 'form'=>'0', 'error' => '0', 'mensaje' => $mensaje, 'id'=>$model->id];
                    }else{
                        Yii::$app->session->setFlash('ok',$mensaje);
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }else
                    $mensaje = Yii::$app->params['operacionFallida'];
            }
            
            //renderizamos las vistas, formulario de carga
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['form' => '1', 'error' => '0', 'mensaje' => $mensaje,
                        'vista' => $this->renderAjax('create', ['model' => $model]
                    )];                
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }            
        }catch(\Exception $e){
            Yii::error('Tipo Documentos - actionCreate'.$e);
            $transaction->rollBack();
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'mensaje' => Yii::$app->params['errorExcepcion']];
            }else{
                Yii::$app->session->setFlash('error',Yii::$app->params['errorExcepcion']);                
            }            
        }        
    } //fin createAjax

    
    /*****************************************************************/
    /*****************************************************************/
    /**
     * Lists all CategoriaServicioOfrecido models.
     * @return mixed
     */
    public function actionIndex()
    {
        try{
            $searchModel = new CategoriaServicioOfrecidoSearch;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);    
        }catch(\Exception $e){
            Yii::error('Index CategoriaServicioOfrecido'.$e);
            Yii::$app->session->setFlash('error',Yii::$app->params['errorExcepcion']);
            return $this->redirect(['site/index']);    
        }
        
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
     * Finds the CategoriaServicioOfrecido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoriaServicioOfrecido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoriaServicioOfrecido::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}