<?php

namespace app\controllers;

use Yii;
use app\models\GrupoFamiliar;
use app\models\search\GrupoFamiliarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use app\models\Persona;
use app\models\Responsable;
use app\models\search\ResponsableSearch;
use app\models\Alumno;
use app\models\search\PersonaSearch;
use app\models\search\ServicioAlumnoSearch;

/**
 * GrupoFamiliarController implements the CRUD actions for GrupoFamiliar model.
 */
class GrupoFamiliarController extends Controller
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
                        'actions' => ['admin','view'],
                        'allow' => true,
                        //'roles' => ['visualizarFamilias'],
                    ],
                    [     
                        'actions' => ['admin','alta','actualizar','view','delete','servicios-familia'],
                        'allow' => true,
                        //'roles' => ['abmlFamilias','gestionarGrupoFamiliar'],
                    ],
                    [     
                        'actions' => ['exportar-excel','down-padron'],
                        'allow' => true,
                        //'roles' => ['exportarFamilias','gestionarGrupoFamiliar'],
                    ],
                    [     
                        'actions' => ['carga-responsable','asignar-responsable','actualizar-responsable','quitar-responsable'],
                        'allow' => true,
                        //'roles' => ['abmlResponsables'],
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
    
    /********************************************************************/
    /********************************************************************/
    /**
     * Lists all GrupoFamiliar models.
     * @return mixed
     */
    public function actionAdmin()
    {
        try{
            $searchModel = new GrupoFamiliarSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);    
        }catch (\Exception $e) {
            Yii::error('Administración Familias '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
            return $this->redirect(['site/index']);            
        }        
    }
    
    /**********************************************************************/
    /**
     * Creates a new GrupoFamiliar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAlta()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{            
            $model = new GrupoFamiliar();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', Yii::$app->params['cargaCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } 
            
            return $this->render('create', [
                'model' => $model,
            ]);           
        }catch (\Exception $e) {
            Yii::error('Alta Familias '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['admin']);            
        }
    }

    /**********************************************************************/
    /**
     * Updates an existing GrupoFamiliar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActualizar($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try{            
            $model = $this->findModel($id);
            
            if ($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', Yii::$app->params['actualizacionCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }             
            return $this->render('create', [
                'model' => $model,
            ]);            
        }catch (\Exception $e) {
            Yii::error('Actualizar Familias '.$e);
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['admin']);            
        }
    }
    
    /**********************************************************************/
    /**********************************************************************/
    /**
     * Deletes an existing GrupoFamiliar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', Yii::$app->params['eliminacionCorrecta']);
            return $this->redirect(['admin']);    
        }catch (\Exception $e) {    
            Yii::error('Delete Familias '.$e);
            Yii::$app->session->setFlash('error', 'No se pudo eliminar el Grupo Familiar.');
            return $this->redirect(['view','id'=>$id]);            
        }        
    }
    
    /**********************************************************************/
    /**********************************************************************/
    /**
     * Displays a single GrupoFamiliar model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        try{
            $model = $this->findModel($id);

            $queryResponsable = Responsable::find()->joinWith(['miPersona p']);
            $queryResponsable->andFilterWhere(['id_grupofamiliar' => $model->id,]);              
            $dataProviderResponsables = new ActiveDataProvider([
                'query' => $queryResponsable,
            ]);        

            $queryAlumnos = Alumno::find()->joinWith(['miPersona p']);
            $queryAlumnos->andFilterWhere(['id_grupofamiliar' => $model->id,]); 
            $dataProviderAlumnos = new ActiveDataProvider([
                'query' => $queryAlumnos,
            ]);        
        
            return $this->render('view', [
                'model' => $this->findModel($id),
                'dataProviderResponsables' => $dataProviderResponsables,
                'dataProviderAlumnos' => $dataProviderAlumnos,
            ]);
        }catch (\Exception $e) {    
            Yii::error('View Familias '.$e);
            Yii::$app->session->setFlash('error', 'No se puede visualizar los datos de la Familia debido a un error.');
            return $this->redirect(['admin']);            
        }   
    }

    /**********************************************************************/
    /**
     * Finds the GrupoFamiliar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrupoFamiliar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrupoFamiliar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    
    
    /**********************************************************************/
    /**********************************************************************/
    /************************ Responsables ********************************/
    
    public function actionCargaResponsable() {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            
            $idFamilia = Yii::$app->request->get('idFamilia');
            
            $modelGrupoFamiliar = GrupoFamiliar::findOne($idFamilia);
            if (!$modelGrupoFamiliar)
                throw new NotFoundHttpException('Grupo Familiar inexistente.'); 
            
            $modelResponsable = new Responsable();
            $modelResponsable->id_grupofamiliar = $idFamilia;

            $modelPersona = new Persona();

            if ($modelResponsable->load(Yii::$app->request->post()) &&
                $modelPersona->load(Yii::$app->request->post())) {

                $valid = $modelPersona->save();
                $modelResponsable->id_persona = $modelPersona->id;
                $valid = $valid && $modelResponsable->save();

                if ($valid){
                    $transaction->commit();                            
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '1', 'message' => Yii::$app->params['cargaCorrecta']];
                }else{
                    $transaction->rollBack();
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '0', 'message' => Yii::$app->params['operacionFallida'],
                        'vista' => $this->renderAjax('_cargaResponsable', [
                            'model' => $modelPersona,
                            'modelResponsable' => $modelResponsable,
                    ])];
                }
            }
            
            Yii::$app->response->format = 'json';
            return ['error' => '0',
                    'vista' => $this->renderAjax('_cargaResponsable', [
                    'model' => $modelPersona,
                    'modelResponsable' => $modelResponsable,
            ])];
        } catch (\Exception $e) {
            Yii::error('Carga Responsbale GrupoFamiliar '.$e);
            $transaction->rollBack();
            throw new NotFoundHttpException('Error al intentar cargar un Responsbale para el Grupo Familiar.');
        }
    }

    /**********************************************************************/
    /**********************************************************************/
    public function  actionActualizarResponsable()
    {               
        try{
            $transaction = Yii::$app->db->beginTransaction();
            
            $idResponsable = Yii::$app->request->get('id');
            
            $modelResponsable = Responsable::findOne($idResponsable);
            if (!$modelResponsable)
                throw new NotFoundHttpException('Responsable inexistente.');
             
            $modelPersona =  Persona::findOne($modelResponsable->id_persona);
           
            if ($modelResponsable->load(Yii::$app->request->post()) && 
                $modelPersona->load(Yii::$app->request->post())){ 
                
                $valid = $modelPersona->save();        
                $modelResponsable->id_persona = $modelPersona->id;
                $valid = $valid && $modelResponsable->save();
                
                    if ($valid){
                        $transaction->commit();                        
                        Yii::$app->response->format = 'json';
                        return ['error' => '0', 'carga'=>'1', 'message' => Yii::$app->params['cargaCorrecta']];
                    }else{  
                        $transaction->rollBack();                    
                        Yii::$app->response->format = 'json';
                        return ['error' => '0','carga' => '0', 'message' => 'ERROR', 
                                'vista' => $this->renderAjax('_cargaResponsable', [
                                    'model' => $modelPersona,
                                    'modelResponsable' => $modelResponsable,
                                ])];
                    }               
            }
            
            //renderizamos las vistas, formulario de carga
            Yii::$app->response->format = 'json';
            return ['error' => '0',
                    'vista' => $this->renderAjax('_cargaResponsable', [
                        'model' => $modelPersona,
                        'modelResponsable' => $modelResponsable,
                    ])];
        }catch(\Exception $e){
            Yii::error('Actualizar Responsbale - GrupoFamiliar '.$e);
            $transaction->rollBack();
            throw new NotFoundHttpException('El Responsbale que intenta actualizar los datos no existe.');
        }        
    } //fin createAjax

    /**********************************************************************/
    /**********************************************************************/
    public function actionAsignarResponsable(){
        $familia = Yii::$app->request->get('familia');
        
        $modelFamilia = GrupoFamiliar::findOne($familia);        
        if(!$modelFamilia)        
            throw new NotFoundHttpException('Grupo Familiar inexistente.');        
        
        try{
            $transaction = Yii::$app->db->beginTransaction(); 
            
            $searchModel = new PersonaSearch();         
            $searchModel->load(Yii::$app->request->get());    
            
            $queryPersonas = Persona::find();
            $queryPersonas->joinWith(['alumnos a']);            
            $dataProvider = new ActiveDataProvider([
                'query' => $queryPersonas,
            ]);
            $queryPersonas->andFilterWhere(['like', 'apellido', $searchModel->apellido])
            ->andFilterWhere(['like', 'nombre', $searchModel->nombre])
            ->andFilterWhere(['like', 'nro_documento', $searchModel->nro_documento]);
             
            $queryPersonas->andWhere(['is','a.id', null]);
            
            if ( !empty(Yii::$app->request->get('familia')) && !empty(Yii::$app->request->get('tipores')) &&
                    !empty(Yii::$app->request->get('idresponsable')) ){
                
                $modelResponsable = new Responsable();
                $modelResponsable->id_grupofamiliar = (int) Yii::$app->request->get('familia');
                $modelResponsable->id_persona = (int) Yii::$app->request->get('idresponsable');
                $modelResponsable->tipo_responsable = Yii::$app->request->get('tipores');
                if($modelResponsable->save()){
                    $transaction->commit();
                    Yii::$app->response->format = 'json';
                    return ['error' => '0','carga' => '1']; 
                }else{
                    Yii::$app->response->format = 'json';
                    return ['error' => '1','carga' => '0','mensaje'=>'no se pudo realiza la asignacion del responsable']; 
                }
            }
                
            Yii::$app->response->format = 'json';
            return ['error' => '0',
                'vista' => $this->renderAjax('_asignarResponsable', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'familia' => $familia
            ])];           
            
        }catch(\Exception $e){
           Yii::error('Asignar Responsbale - GrupoFamiliar '.$e);
           throw new NotFoundHttpException('Grupo Familiar inexistente.');
         }
    }
    
    /**********************************************************************/
    /**********************************************************************/
    public function  actionQuitarResponsable($id)
    {
        $transaction = Yii::$app->db->beginTransaction();       
        
        try{
            if (($model = Responsable::findOne($id)) !== null) {
                $model->delete();
                $transaction->commit();
                Yii::$app->response->format = 'json';
                return ['error' => '0', 'mensaje' => Yii::$app->params['eliminacionCorrecta']];                
            }else{
                throw new NotFoundHttpException('EL Responsable que intenta eliminar no existe.');
            }
        }catch(\Exception $e){
            Yii::error('Quitar Responsbale - GrupoFamiliar '.$e);
            $transaction->rollBack();
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'mensaje' => 'El Responsable que intenta eliminar no existe.'];
        }        
    } //fin QuitarResponsable
    /***********************   FIN RESPONSBALES ***************************/ 
    
    
    /**********************************************************************/
    /**********************************************************************/
    /************************ Bonificaciones ******************************/
    /*
    public function actionAsignarBonificacion($familia){        
        try{
            $model = new \app\models\BonificacionFamiliar();
            $model->id_grupoFamiliar = $familia;        

                if ($model->load(Yii::$app->request->post())){
                if(!empty(\app\models\BonificacionFamiliar::find()->where("id_grupofamiliar=$familia and  id_bonificacion=$model->id_bonificacion")->all())){
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '1','message' => 'No se pudo asignar la bonificación. La misma ya se encuentra asignada'];    
                }else
                if($model->save()){
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '1','message' => Yii::$app->params['cargaCorrecta']];    
                }else{
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '0','message' => 'dfg',
                        'vista' => $this->renderAjax('_formAsignacionBonificacion', [
                                        'model' => $model,
                                     ])];
                }
            }    
        }catch(\Exception $e){
            
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'message' => Yii::$app->params['errorExcepcion']];
        }    
        
        
        return $this->renderAjax('_formAsignacionBonificacion',['model'=>$model]);
    }
    */
    /**********************************************************************/
    /*
    public function actionQuitarBonificacion($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            if ( \app\models\BonificacionFamiliar::findOne($id)->delete() ){
                $transaction->commit();
                if (Yii::$app->request->isAjax){                    
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'message' => Yii::$app->params['eliminacionCorrecta']];
                }
            }
        }
        catch (\Exception $e){
            $transaction->rollBack();
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'message' =>  Yii::$app->params['errorExcepcion']];
            }
        }
    }    
    */
    /**********************************************************************/
    /**********************************************************************/
    /************************  ******************************/
    public function actionServiciosFamilia($familia){        
        try{
            $modelFamilia = $this->findModel($familia);
            
            $searchModel = new ServicioAlumnoSearch();
            $searchModel->familia = $familia;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('serviciosFamiliar', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$modelFamilia
            ]);            
        }
        catch (\Exception $e){
            Yii::$app->session->setFlash('error', 'No se puede visualizar los datos de la Familia debido a un error.');
            return $this->redirect(['view','id'=>$modelFamilia->id]);   
        }
        
    }
    
    
    /*******************************************************************/
    /******************** EXPORTACION A EXCEL **************************/            
    public function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color) ));
    }  
    
    public function actionExportarExcel() {       
        
        if(Yii::$app->session->has('padronfamilias')){
            
            $data = Yii::$app->session->get('padronfamilias');   
            
            ini_set('memory_limit', -1);
            set_time_limit(0);
            
            $i = 0;                        
            $contador = count($data);

            if ($contador > 0) {
                $objPHPExcel = new \PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
              
                $this->cellColor($objPHPExcel, 'A1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'B1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'C1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'D1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'E1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'F1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'G1', 'F28A8C');
                
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'APELLIDOS');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'DESCRIPCION');                
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nº Hijos');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'HIJOS');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'PAGO ASOCIADO');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'TC/CBU');
             
               
                $letracolumnainicio = 'A';
                $letrafilainicio = 3;

                while ($i < $contador) {
                    $letrafilainicio1 = (string) $letrafilainicio;
                    $columnaA = 'A' . $letrafilainicio1;
                    $columnaB = 'B' . $letrafilainicio1;
                    $columnaC = 'C' . $letrafilainicio1;
                    $columnaD = 'D' . $letrafilainicio1;
                    $columnaE = 'E' . $letrafilainicio1;
                    $columnaF = 'F' . $letrafilainicio1;
                    $columnaG = 'G' . $letrafilainicio1;
              
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $data[$i]["apellidos"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $data[$i]["folio"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, $data[$i]["descripcion"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, $data[$i]["cantidadHijos"] );
                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaE, $data[$i]["datosMisHijos"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaF, $data[$i]["idPagoAsociado"]["nombre"]);
                    
                    if($data[$i]["id_pago_asociado"]=='4')
                        $objPHPExcel->getActiveSheet()->setCellValue($columnaG, $data[$i]["cbu_cuenta"]);
                    elseif($data[$i]["id_pago_asociado"]=='5')
                        $objPHPExcel->getActiveSheet()->setCellValue($columnaG, $data[$i]["nro_tarjetacredito"]);
                    
                              
                    $i = $i + 1;
                    $letrafilainicio += 1;
                }  
                
                $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
                $nombre_archivo = "listadoFamilias" . Yii::$app->user->id . ".xlsx";                                
                $ruta_archivo = $carp_cont . "/" . $nombre_archivo;
            
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($ruta_archivo);
                $url_pdf = \yii\helpers\Url::to(['grupo-familiar/down-padron', 'id' => $nombre_archivo]);
                
                Yii::$app->response->format = 'json';
                return ['result_error' => '0', 'result_texto' => $url_pdf];
                
            }else{
                Yii::$app->response->format = 'json';
                return ['result_error' => '1', 'message' => 'LISTADO VACIO'];
            }
        }
    }

    public function actionDownPadron() {        
        $name = $_GET["id"];        
        $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos                                       
        $ruta_archivo = $carp_cont . "/" . $name;
        
        if (is_file($ruta_archivo)) {
            $size = filesize($ruta_archivo);
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=listadoFamilias.xlsx");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);
            readfile($ruta_archivo);
        }
    }
}