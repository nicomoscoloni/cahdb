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
                        'actions' => ['index','delete','alta','view','update','view'],
                        'allow' => true,
                        //'roles' => ['abmlEstablecimientos'],
                    ],
                    [     
                        'actions' => ['nuevo-servicio','servicios-division','eliminar-servicio','hola','asignar-servicio-division'],
                        'allow' => true,
                        //'roles' => ['perServiciosEstablecimientos'],
                    ],
                    [     
                        'actions' => ['cargar-division','actualizar-division','eliminar-division'],
                        'allow' => true,
                        //'roles' => ['gestionarDivisionEscolar'],
                    ],
                    [     
                        'actions' => ['mis-divisionesescolares'],
                        'allow' => true,
                        //'roles' => ['filtrarDivisionesxEstablecimiento'],
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
            return $this->redirect(['index']);    
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
    public function actionIndex()
    {
        try{
            $searchModel = new EstablecimientoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);    
        }catch (\Exception $e) { 
            Yii::error('Establecimiento Index '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['view','id'=>$id]);                        
        }
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
            
            return $this->render('create', [
                'model' => $model,
            ]);
            
        }catch (\Exception $e) {
            Yii::error('Establecimiento Alta '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['index']);                        
        }
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
            return $this->render('update', [
                'model' => $model,
            ]);
            
        }catch (\Exception $e) {
            Yii::error('Establecimiento Update '.$e);
            $transaction->rollBack(); 
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['index']);
        }
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

            //modelo y dataprovider de Divisiones Escolares del Establecimiento
            $searchModelDivisiones = new DivisionEscolarSearch();
            $searchModelDivisiones->id_establecimiento = $id;
            $dataProviderDivisiones = $searchModelDivisiones->search(Yii::$app->request->queryParams);

            //modelo y dataprovider de Alumnos del Establecimiento
            $modelPersona =  new Persona();
            $modelPersona->load(Yii::$app->request->queryParams); 
            $searchModelAlumnos = new AlumnoSearch();
            $searchModelAlumnos->establecimiento = $id;
            $dataProviderAlumnos = $searchModelAlumnos->search(Yii::$app->request->queryParams,$modelPersona);

            //modelo y dataprovider de Servicios Establecimiento
            $searchModelSerEst = new ServicioEstablecimientoSearch();
            $searchModelSerEst->establecimiento = $id;
            $dataProviderSerEst = $searchModelSerEst->search(Yii::$app->request->queryParams);

            return $this->render('view', [
                    'model' => $this->findModel($id),                    
                    'dataProviderDivisiones' =>$dataProviderDivisiones,
                    'searchModelDivisiones' => $searchModelDivisiones,                      
                    'modelPersona'=> $modelPersona,
                    'dataProviderAlumnos' =>$dataProviderAlumnos,
                    'searchModelAlumnos' => $searchModelAlumnos,                    
                    'dataProviderSerEst' =>$dataProviderSerEst,
                    'searchModelSerEst' => $searchModelSerEst,            
                ]);    
        }catch (\Exception $e) {
            Yii::error('Establecimiento View '.$e);           
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['index']);
        }          
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
    
    public function actionMisDivisionesescolares($idEst){
        
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
    }
    
    /************************************************************************/
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
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'message' => Yii::$app->params['errorExcepcion']];
        }
    }
    
    /************************************************************************/
    public function actionActualizarDivision($id){
        
        $transaction = Yii::$app->db->beginTransaction();        
        try{
            $model =  DivisionEscolar::findOne($id); 
            
            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    $mensaje = Yii::$app->params['cargaCorrecta'];
                    $transaction->commit();
                    Yii::$app->response->format = 'json';
                    return ['carga' => '1', 'error' => '0', 'message' => $mensaje, 'id'=>$model->id];    
                }else{
                    $transaction->rollBack();
                    Yii::$app->response->format = 'json';
                    return ['carga' => '0', 'error' => '0', 'message' => Yii::$app->params['operacionFallida'], 
                                'vista' => $this->renderAjax('_formDivision', [
                                        'model' => $model,
                                ])];
                }
            }
            
                    //renderizamos las vistas, formulario de carga
                    Yii::$app->response->format = 'json';
                    return ['carga' => '0', 'error' => '0', 
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
      
    /************************************************************************/
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
    public function actionNuevoServicio($est){
        
        $modelEstablecimiento = $this->findModel($est);
            
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = new ServicioEstablecimiento();
            $model->establecimiento  = $modelEstablecimiento->id;
            
            if ($model->load(Yii::$app->request->post())){
                $valid = true;
                
                //buscamos los servicios
                if(!empty($model->divisiones)){
                    foreach($model->divisiones as $key => $value){
                        $modelSE = new ServicioEstablecimiento();
                        $modelSE->id_divisionescolar = $value;
                        $modelSE->id_servicio = $model->id_servicio;
                        $valid = $valid && $modelSE->save();
                    }
                    
                    if($valid){
                        $transaction->commit();
                        Yii::$app->session->setFlash('ok', Yii::$app->params['cargaCorrecta']);
                        return $this->redirect(['establecimiento/view','id'=>$est]);
                    }
                }else
                    $model->addError('divisiones','Ingrese al menos una division');    
            }
            return $this->render('_createServicio', [
                    'model' => $model,
                ]);
        }
        catch (\Exception $e){
                Yii::error('Establecimiento - actionNuevoServicio '.$e);
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'NO SE PUDO CARGAR EL SERVICIO AL ESTABLECIMIENTO');
                return $this->redirect(['establecimiento/view','id'=>$est]);
        }
        
    }
    
    /************************************************************************/
   
    
    
    public function actionHola($idEst,$idServ){
        try{            
            $queryDivisionesConServicio = DivisionEscolar::find()->joinWith(['miServicios s']);
            $queryDivisionesConServicio
                ->andWhere(['id_establecimiento' => (int) $idEst])
                ->andWhere(['is','s.id_servicio' , $idServ]);
            
            $queryDivisionesSinServicio = DivisionEscolar::find()->joinWith(['miServicios s']);
            $queryDivisionesSinServicio
                ->andWhere(['id_establecimiento' => (int) $idEst])
                ->andWhere(['is','s.id_servicio' , null]);
            
            $divisionesConServicio = new ActiveDataProvider([
                'query' => $queryDivisionesConServicio,           
                'pagination' => false
            ]);
            
            $divisionesSinServicio = new ActiveDataProvider([
                'query' => $queryDivisionesSinServicio,           
                'pagination' => false
            ]);
            
            Yii::$app->response->format = 'json';
            return ['error' => '0',
                'vista' => $this->renderAjax('_divisionesServicio', [
                    'divisionesConServicio' => $divisionesConServicio,
                    'divisionesSinServicio' => $divisionesSinServicio,
                    'servicio'=>$idServ,
            ])];
            
            
            
            
           
           
        }catch (\Exception $e) {
            Yii::error('Establecimiento - actionHola '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
            return $this->redirect(['index']);
            exit;
        } 
    }
    
    
    /***********************************************************/
    /***********************************************************/
    public function actionAsignarServicioDivision()
    {
        try{
            $transaction = Yii::$app->db->beginTransaction();
        
            $division = Yii::$app->request->get('division');
            $servicio = Yii::$app->request->get('servicio');
            
            $modelServicio = \app\models\ServicioOfrecido::findOne($servicio);
            if(!$modelServicio)
                throw new NotFoundHttpException('Servicio Ofrecido inexistente.');
            
            $modelDivision = \app\models\DivisionEscolar::findOne($division);
            if(!$modelDivision)
                throw new NotFoundHttpException('Divisiòn escolar inexistente.');

            
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
    

    /***********************************************************/
    /***********************************************************/
    public function actionQuitarServicio($idOrden=null,$idServicio=null)
    {
        try{
            $transaction = Yii::$app->db->beginTransaction();
        
            $modelServicio = \app\models\ServicioPagado::findOne($idServicio);
            if(!$modelServicio)
                throw new NotFoundHttpException('No existe el Servicio Pagado.');

            $modelOrdenPago = \app\models\OrdenPago::findOne($idOrden);
            if(!$modelOrdenPago)
                throw new NotFoundHttpException('No existe la Orden de Pago.');
            
            $modelServicio->id_ordenpago = null;
            $valid = $modelServicio->save();
            //a  veces tira problema suma o resta ambas veces
            $modelOrdenPago->saldo = $modelOrdenPago->sumaTotalServicios; 
            if($valid && $modelOrdenPago->save()){
                $transaction->commit();
                Yii::$app->response->format = 'json';
                return ['error' => '0'];                
            }   
        }catch (\Exception $e){
            Yii::error('Quitarr Servicio Orden Pago' . $e); 
            $transaction->rollBack();
            Yii::$app->response->format = 'json';
            return ['error' => '1'];
            
        }    
            
        
    }    
    
}
