<?php

namespace app\controllers;

use Yii;
use app\models\Establecimiento;
use app\models\search\EstablecimientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use app\models\DivisionEscolar;
use app\models\search\DivisionEscolarSearch;
use app\models\search\AlumnoSearch;
use app\models\Persona;
use app\models\ServicioEstablecimiento;
use app\models\search\ServicioEstablecimientoSearch;


/**
 * EstablecimientoController implements the CRUD actions for Establecimiento model.
 */
class EstablecimientoController extends Controller
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
                        'actions' => ['admin',],
                        'allow' => true,
                        'roles' => ['listarEstablecimientos'],
                    ],
                    [     
                        'actions' => ['alta','update'],
                        'allow' => true,
                        'roles' => ['cargarEstablecimiento'],
                    ],
                    [     
                        'actions' => ['delete',],
                        'allow' => true,
                        'roles' => ['eliminarEstablecimiento'],
                    ],
                    [     
                        'actions' => ['view','mis-alumnos'],
                        'allow' => true,
                        'roles' => ['visualizarEstablecimiento'],
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
     * Deletes an existing Establecimiento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    { 
        try{
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success',Yii::$app->params['eliminacionCorrecta']);
            return $this->redirect(['admin']);    
        }catch (\Exception $e) { 
            Yii::error('Establecimiento Delete '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['view','id'=>$id]);                        
        }
    }

    /************************************************************************/
    /************************************************************************/
    /**
     * Lists all Establecimiento models.
     * @return mixed
     */
    public function actionAdmin()
    {
        try{
            $searchModel = new EstablecimientoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }catch (\Exception $e) { 
            Yii::error('Establecimiento Index '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /************************************************************************/
    /************************************************************************/
    /**
     * Creates a new Establecimiento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAlta()
    {
        $transaction = Yii::$app->db->beginTransaction();        
        try{
            $model = new Establecimiento();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }catch (\Exception $e) {
            Yii::error('Establecimiento Alta '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    

    /************************************************************************/
    /************************************************************************/
    /**
     * Updates an existing Establecimiento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();        
        try{
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success',Yii::$app->params['actualizacionCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } 
        }catch (\Exception $e) {
            Yii::error('Establecimiento Update '.$e);
            $transaction->rollBack(); 
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);

    }
    
    /************************************************************************/
    /************************************************************************/
    /**
     * Displays a single Establecimiento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        try{            
            $model = $this->findModel($id);
        }catch (\Exception $e) {
            Yii::error('Establecimiento View '.$e);           
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);            
        }         
        return $this->render('view', [
            'model' => $this->findModel($id),  
        ]);    
    } 
    
    /************************************************************************/
    /**
     * Finds the Establecimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Establecimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Establecimiento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    /************************************************************************/
    /************************************************************************/
    /**************************** DIVISIONES ESCOLARES **********************/
    public function actionDropMisDivisionesescolares($idEst){
        try{
            $countDivisiones = DivisionEscolar::find()
                    ->where(['id_establecimiento' => $idEst])
                    ->count();

            $divisiones = DivisionEscolar::find()
                    ->where(['id_establecimiento' => $idEst])
                    ->orderBy('id')
                    ->all();   

            if($countDivisiones>0){
                echo "<option value=''>Select.</option>";
                foreach($divisiones as $one){
                    echo "<option value='".$one->id."'>".$one->nombre."</option>";
                }
            }
            else{
                echo "<option value=''></option>";
            }    
        }catch(\Exception $e){
            Yii::error('Establecimiento - Cargar Division '.$e);
            echo "<option value=''></option>";
        }
         
    }
    
    /************************************************************************/
    /**************************DIVISIONES ESCO*******************************/
    /*
     * Retorna un dataprovider de division escolares
     */
    public function actionMisDivisionesEscolares(){
        try{
            $establecimiento = Yii::$app->request->get('establecimiento');        
            $modelEstablecimiento = $this->findModel($establecimiento);

            //modelo y dataprovider de Divisiones Escolares del Establecimiento
            $searchModelDivisiones = new DivisionEscolarSearch();
            $searchModelDivisiones->id_establecimiento = $establecimiento;
            $dataProviderDivisiones = $searchModelDivisiones->search(Yii::$app->request->queryParams);        

            return $this->renderAjax('_misDivisiones', [
                'modelEstablecimiento' => $modelEstablecimiento,                    
                'dataProviderDivisiones' =>$dataProviderDivisiones,
            ]); 
        }catch(\Exception $e){
            Yii::error('Establecimiento - Cargar Division '.$e);            
            Yii::$app->response->format = 'json';
            throw new \yii\web\HttpException(500,'No se puden mostrar las divisiones escolares.');
        }
    }
    
    public function actionCargarDivision($est){        
        $transaction = Yii::$app->db->beginTransaction();        
        try{
            $modelEstablecimiento = $this->findModel($est);
            
            $model = new DivisionEscolar();
            $model->id_establecimiento = $modelEstablecimiento->id;            
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    $mensaje = Yii::$app->params['cargaCorrecta'];
                    $transaction->commit();
                    Yii::$app->response->format = 'json';
                    return ['carga' => '1', 'form' => '0', 'error' => '0', 'mensaje' => $mensaje, 'id'=>$model->id];    
            } 
            //renderizamos las vistas, formulario de carga
            Yii::$app->response->format = 'json';
            return ['form' => '1', 'error'=>'0',
                'vista' => $this->renderAjax('_formDivision', [
                    'model' => $model,
            ])];
        }catch(\Exception $e){
            Yii::error('Establecimiento - Cargar Division '.$e);
            $transaction->rollBack();
            throw new \yii\web\HttpException(500,'Error');
        }
    }
    
    public function actionActualizarDivision($id){        
        $transaction = Yii::$app->db->beginTransaction();        
        try{
            $model =  DivisionEscolar::findOne($id); 
            
            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    $mensaje = Yii::$app->params['cargaCorrecta'];
                    $transaction->commit();
                    Yii::$app->response->format = 'json';
                    return ['carga' => '1', 'form' => '0', 'error' => '0', 'message' => $mensaje, 'id'=>$model->id];    
                }
            }
            
            //renderizamos las vistas, formulario de carga
            Yii::$app->response->format = 'json';
            return  ['form' => '1', 'error'=>'0',
                        'vista' => $this->renderAjax('_formDivision', [
                                'model' => $model,
                    ])];         
        }catch(\Exception $e){
            Yii::error('Establecimiento - actionActualizarDivision '.$e);
            $transaction->rollBack();
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'message' => Yii::$app->params['errorExcepcion']];
        }
    } 
      
    public function actionEliminarDivision($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            if ( DivisionEscolar::findOne($id)->delete() ){
                $transaction->commit();
               /* if (Yii::$app->request->isAjax){ */                   
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'message' => Yii::$app->params['eliminacionCorrecta']];
               /* }else{
                    Yii::$app->session->setFlash('ok',Yii::$app->params['eliminacionCorrecta']);
                    return $this->redirect(['index']);
                }*/
            }
        }
        catch (\Exception $e){
            $transaction->rollBack();
           /* if (Yii::$app->request->isAjax){*/
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'message' =>  Yii::$app->params['errorExcepcion']];
            /*}else{
                Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
                return $this->redirect(['index']);
            }*/
        }
    }
    
    
    /************************************************************************/
    /************************************************************************/
    /**************************** SERVICIOS OFRECIDOS  **********************/
    public function actionMisServiciosOfrecidos(){
        try{
            $establecimiento = Yii::$app->request->get('establecimiento');        
            $modelEstablecimiento = $this->findModel($establecimiento);

            //modelo y dataprovider de Servicios Establecimiento
            $searchModelSerEst = new ServicioEstablecimientoSearch();
            $searchModelSerEst->establecimiento = $establecimiento;
            $dataProviderSerEst = $searchModelSerEst->search(Yii::$app->request->queryParams);  

            return $this->renderAjax('_misServicios', [
                'modelEstablecimiento' => $modelEstablecimiento,                    
                'dataProviderSerEst' =>$dataProviderSerEst,
                'searchModelSerEst' => $searchModelSerEst,                 
            ]); 
        }catch(\Exception $e){
            Yii::error('Establecimiento - Cargar Division '.$e);            
            Yii::$app->response->format = 'json';
            throw new \yii\web\HttpException(500,'No se puden mostrar los servicios del establecimiento.');            
        }
    }
    
    public function actionNuevoServicio(){        
        $modelEstablecimiento = $this->findModel(Yii::$app->request->get('est'));        
        try{
            $model = new ServicioEstablecimiento();
            $model->establecimiento  = $modelEstablecimiento->id;
            
            $queryDivisiones = DivisionEscolar::find()->andWhere(['id_establecimiento' => $modelEstablecimiento->id]);            
            $dataProviderDivisiones = new ActiveDataProvider([
                'query' => $queryDivisiones,           
                'pagination' => false
            ]);
        }
        catch (\Exception $e){
            Yii::error('Establecimiento - actionNuevoServicio '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'NO SE PUDO CARGAR EL SERVICIO AL ESTABLECIMIENTO');
        }
        return $this->render('asignarServicio', [
            'model' => $model,
            'dataProviderDivisiones'=>$dataProviderDivisiones
        ]);
    }    
    
    public function actionGetServicios(){
        $modelEstablecimiento = $this->findModel(Yii::$app->request->get('idEst'));
        
        $modelServicio = \app\models\ServicioOfrecido::findOne(Yii::$app->request->get('idServ'));
        if(empty($modelServicio))
           throw new NotFoundHttpException('Servicio Ofrecido inexistente.');
        
        try{
            $queryDivisiones = DivisionEscolar::find()->andWhere(['id_establecimiento' => $modelEstablecimiento->id]);            
            $dataProviderDivisiones = new ActiveDataProvider([
                'query' => $queryDivisiones,           
                'pagination' => false
            ]);
            
            $queryDivisionesConServicio = DivisionEscolar::find()->joinWith(['miServicios s']);
            $divisionesConServicio = $queryDivisionesConServicio 
                ->andFilterWhere(['id_establecimiento' => $modelEstablecimiento->id])
                ->andFilterWhere(['s.id_servicio' => $modelServicio->id])->asArray()->all();
            $divisionesConServicio = \yii\helpers\ArrayHelper::map($divisionesConServicio, 'id','nombre');
            
            Yii::$app->response->format = 'json';
            return ['error' => '0',
                'vista' => $this->renderAjax('_divisiones-del-servicio', [
                    'modelEstablecimiento'=>$modelEstablecimiento,
                    'modelServicio'=>$modelServicio,
                    'dataProviderDivisiones' => $dataProviderDivisiones,
                    'divisionesConServicio' => $divisionesConServicio,                    
            ])];
        }catch (\Exception $e) {
            Yii::error('Establecimiento - actionHola '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
            return $this->redirect(['index']);
            exit;
        } 
    }   
   
    public function actionAsignarServicioDivision()
    {
        $transaction = Yii::$app->db->beginTransaction();

        $division = Yii::$app->request->get('division');
        $servicio = Yii::$app->request->get('servicio');

        $modelServicio = \app\models\ServicioOfrecido::findOne($servicio);
        if(!$modelServicio)
            throw new NotFoundHttpException('Servicio Ofrecido inexistente.');

        $modelDivision = \app\models\DivisionEscolar::findOne($division);
        if(!$modelDivision)
            throw new NotFoundHttpException('Divisiòn escolar inexistente.');

        try{
            $modelServicioEstablecimiento = new ServicioEstablecimiento();
            $modelServicioEstablecimiento->id_servicio = $modelServicio->id;
            $modelServicioEstablecimiento->id_divisionescolar = $modelDivision->id;
            
            if($modelServicioEstablecimiento->save()){
                $transaction->commit();
                Yii::$app->response->format = 'json';
                return ['error' => '0'];                
            }else{
                $transaction->rollBack();
                Yii::$app->response->format = 'json';
                return ['error' => '1'];         
            }   
        }catch (\Exception $e){
            Yii::error('Asignar Servicio Orden Pago' . $e); 
            $transaction->rollBack();
            Yii::$app->response->format = 'json';
            return ['error' => '1'];            
        }   
    }    
    
    public function actionQuitarServicioDivision()
    {
        $transaction = Yii::$app->db->beginTransaction();

        $division = Yii::$app->request->get('division');
        $servicio = Yii::$app->request->get('servicio');

        $modelServicio = \app\models\ServicioOfrecido::findOne($servicio);
        if(!$modelServicio)
            throw new NotFoundHttpException('Servicio Ofrecido inexistente.');

        $modelDivision = \app\models\DivisionEscolar::findOne($division);
        if(!$modelDivision)
            throw new NotFoundHttpException('Divisiòn escolar inexistente.');

        try{
            
            
            $modelServicioEscolar = \app\models\ServicioEstablecimiento::find()
                   ->andWhere(['id_divisionescolar' => $division])
                   ->andWhere(['id_servicio' => $servicio])->one();
                    
            if($modelServicioEscolar->delete()){
                $transaction->commit();
                Yii::$app->response->format = 'json';
                return ['error' => '0'];                
            }else{
                $transaction->rollBack();
                Yii::$app->response->format = 'json';
                return ['error' => '1'];         
            }   
        }catch (\Exception $e){
            Yii::error('Asignar Servicio Orden Pago' . $e); 
            $transaction->rollBack();
            Yii::$app->response->format = 'json';
            return ['error' => '1'];            
        }   
    }   
    
    /************************************************************************/
    /************************************************************************/
    /**************************** SERVICIOS OFRECIDOS  **********************/
    public function actionMisAlumnos(){
        try{
            $establecimiento = Yii::$app->request->get('establecimiento');        
            $modelEstablecimiento = $this->findModel($establecimiento);
            
            
            //modelo y dataprovider de Alumnos del Establecimiento
            $modelPersona =  new Persona();
            $modelPersona->load(Yii::$app->request->queryParams); 
            $searchModelAlumnos = new AlumnoSearch();
            $searchModelAlumnos->establecimiento = $modelEstablecimiento->id;
            $dataProviderAlumnos = $searchModelAlumnos->search(Yii::$app->request->queryParams,$modelPersona);

            return $this->renderAjax('_misAlumnos', [
                'modelEstablecimiento' => $modelEstablecimiento,                    
                'dataProviderAlumnos' =>$dataProviderAlumnos,
                'searchModelAlumnos' => $searchModelAlumnos,   
                'modelPersona' => $modelPersona,
            ]); 
        }catch(\Exception $e){
            Yii::error('Establecimiento - Cargar Division '.$e);            
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'message' => Yii::$app->params['errorExcepcion']];
        }
        
            
    }
 

    
    
}
