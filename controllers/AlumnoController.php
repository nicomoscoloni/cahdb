<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Alumno;
use app\models\search\AlumnoSearch;
use app\models\Persona;
use app\models\GrupoFamiliar;

/**
 * AlumnoController implements the CRUD actions for Alumno model.
 */
class AlumnoController extends Controller
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
                        'actions' => ['listado','mis-divisionesescolares'],
                        'allow' => true,
                        'roles' => ['listarAlumnos'],
                    ],
                    [     
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['eliminarAlumno'],
                    ],
                    [     
                        'actions' => ['empadronamiento','buscarFamilia','mis-divisionesescolares'],
                        'allow' => true,
                        'roles' => ['cargarAlumno'],
                    ],
                    [     
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['visualizarAlumno'],
                    ],
                    [     
                        'actions' => ['activar'],
                        'allow' => true,
                        'roles' => ['activarAlumno'],
                    ],
                    [     
                        'actions' => ['inactivar'],
                        'allow' => true,
                        'roles' => ['inactivarAlumno'],
                    ],                    
                    [     
                        'actions' => ['down-padron','exportar-excel'],
                        'allow' => true,
                        'roles' => ['exportarAlumnos'],
                    ],
                    [     
                        'actions' => ['asignar-bonificacion','quitar-bonificacion','eliminar-bonificacion'],
                        'allow' => true,
                        'roles' => ['gestionarBonificacionAlumno'],
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
    
    
    
    
    public function actions()
    {
        return [
            // declares "error" action using a class name
            'buscarFamilia' => 'app\actions\BuscarFamiliaAction',            
        ];
    } 
    
    /***************************************************************/
    /***************************************************************/
    /**
     * Finds the Alumno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Alumno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alumno::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /***************************************************************/
    /***************************************************************/
    /**
     * Lists all Alumno models.
     * @return mixed
     */
    public function actionListado()
    {
        try{
            $modelPersona =  new Persona();
            $modelPersona->load(Yii::$app->request->queryParams);
            
            $searchModel = new AlumnoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPersona); 
        }catch (\Exception $e) {
            Yii::error('Administración Alumnos '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);                      
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelPersona'=> $modelPersona,
        ]);
    }

    /*******************************************************************/
    /********************** Eliminacion ********************************/ 
    /**
     * Deletes an existing Alumno model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{            
            $this->findModel($id)->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('error',Yii::$app->params['eliminacionCorrecta']);
            return $this->redirect(['listado']);    
        }
        catch (\Exception $e){
            Yii::error('Eliminación Alumnos '.$e);
            Yii::$app->session->setFlash('error',Yii::$app->params['eliminacionErronea']);
            $transaction->rollBack();            
            return $this->redirect(['view','id'=>$id]);
        }
    }        
     
    public function actionActivar($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            $model = $this->findModel($id);
            $model->activo ='1';
            
            if ( $model->save() ){
                $transaction->commit();                                    
                Yii::$app->response->format = 'json';
                return ['error' => '0', 'mensaje' => 'Se Activo Correctamente!!!'];                
            }
            else{
                $transaction->rollBack();                                  
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'mensaje' => 'Error al activar al Alumno!!!'];
            }
        }
        catch (\Exception $e){
            Yii::error('Activar Alumnos '.$e);
            $transaction->rollBack();            
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'mensaje' =>  'Error al activar al Alumno!!!'];            
        }
    }
    
    public function actionInactivar($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            $model = $this->findModel($id);
            $model->activo = '0';
            if ( $model->save()){
                $transaction->commit();                                  
                Yii::$app->response->format = 'json';
                return ['error' => '0', 'mensaje' => 'Se Inactivo Correctamente!!!'];                
            }else{
                $transaction->rollBack();                                  
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'mensaje' => 'Error al inactivar al Alumno!!!'];
            }
        }
        catch (\Exception $e){
            Yii::error('Inactivar Alumnos '.$e);
            $transaction->rollBack();            
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'mensaje' => 'Error al inactivar al Alumno!!!'];            
        }
    }   
    
    /***************************************************************/
    /**************************************************************/
    /**
     * Creates a new Alumno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEmpadronamiento($id=null)
    {   
        $transaction = Yii::$app->db->beginTransaction();    
        
        try {
            if(!empty($id)){
                $modelAlumno = $this->findModel($id);                
                $modelPersona = Persona::findOne($modelAlumno->id_persona);
                $modelGrupoFamiliar = GrupoFamiliar::findOne($modelAlumno->id_grupofamiliar);
                $modelAlumno->establecimiento = \app\models\DivisionEscolar::findOne($modelAlumno->id_divisionescolar)->id_establecimiento;
            }
            else{    
                $modelPersona = new Persona();
                $modelAlumno = new Alumno();
                $modelAlumno->activo = '1';
                $modelGrupoFamiliar = new GrupoFamiliar();
            }

            if(!empty(Yii::$app->request->post('mifamilia')))
                $modelGrupoFamiliar = GrupoFamiliar::findOne(Yii::$app->request->post('mifamilia'));
            
            if ($modelAlumno->load(Yii::$app->request->post()) && $modelPersona->load(Yii::$app->request->post()) &&
                $modelPersona->validate() ) 
            {
                                
                if(!$modelGrupoFamiliar){
                    $modelAlumno->addError('id_grupofamiliar','Ingrese la Familia');
                    $modelGrupoFamiliar = new GrupoFamiliar();
                }else{
                    $modelAlumno->id_grupofamiliar = $modelGrupoFamiliar->id;      
                    if($modelPersona->save()){ 
                        $modelAlumno->id_persona = $modelPersona->id;

                        if($modelAlumno->save()){
                            Yii::$app->session->setFlash('ok',Yii::$app->params['cargaCorrecta']);
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $modelAlumno->id]);
                        }
                    }
                }
            } 
        }
        catch (\Exception $e){
            Yii::error('Empadronamiento Alumnos '.$e);
            Yii::$app->session->setFlash('error',Yii::$app->params['operacionFallida']);
            $transaction->rollBack();  
        }    
        return $this->render('create', [
            'modelAlumno' => $modelAlumno,
            'modelPersona' => $modelPersona,
            'modelGrupoFamiliar' =>  $modelGrupoFamiliar,
        ]);
    }

    
    /***************************************************************/
    /**************************************************************/
    /**
     * Displays a single Alumno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        try{
            $misBonificaciones = new \yii\data\ActiveDataProvider([
                    'query' => \app\models\BonificacionAlumno::find()->where('id_alumno='.$id),
                ]);        

            $searchMisServicios = new \app\models\search\ServicioAlumnoSearch(); 
            $searchMisServicios->id_alumno = $id;        
            $misServicios = $searchMisServicios->search(Yii::$app->request->queryParams);        

            
        }catch (\Exception $e) {
            Yii::error('View Alumnos '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
            return $this->redirect(['site/index']);            
        }  
        return $this->render('view', [
            'model' => $this->findModel($id),
            'misBonificaciones' => $misBonificaciones,
            'misServicios' => $misServicios,
            'searchMisServicios'=>$searchMisServicios
        ]);        
        
    }
    
    
    /**********************************************************************/
    /************************ Bonificaciones ******************************/
    public function actionAsignarBonificacion($alumno){        
        try{
            
            $model = new \app\models\BonificacionAlumno();
            $model->id_alumno = $alumno;        

            if ($model->load(Yii::$app->request->post())){
                if(!empty(\app\models\BonificacionAlumno::find()->where("id_alumno=$alumno and  id_bonificacion=$model->id_bonificacion")->all())){
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '1','mensaje' => 'No se pudo asignar la bonificación. La misma ya se encuentra asignada'];    
                }else
                if($model->save()){
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '1','mensaje' => Yii::$app->params['cargaCorrecta']];    
                }else{
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'carga' => '0','mensaje' => 'dfg',
                        'vista' => $this->renderAjax('_formAsignacionBonificacion', [
                                        'model' => $model,
                                     ])];
                }
            }    
        }catch(\Exception $e){ 
            Yii::error('Asignar Bonificacion Alumnos '.$e);
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'mensaje' => Yii::$app->params['errorExcepcion']];
        } 
            Yii::$app->response->format = 'json';
            return ['error' => '0',
                'vista' => $this->renderAjax('_formAsignacionBonificacion', [
                                'model' => $model,
                             ])];
        
    }
    
    /**********************************************************************/
    public function actionQuitarBonificacion($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            if ( \app\models\BonificacionAlumno::findOne($id)->delete() ){
                $transaction->commit();
                if (Yii::$app->request->isAjax){                    
                    Yii::$app->response->format = 'json';
                    return ['error' => '0', 'mensaje' => Yii::$app->params['eliminacionCorrecta']];
                }
            }
        }
        catch (\Exception $e){
            Yii::error('Quitar Bonificacion Alumnos '.$e);
            $transaction->rollBack();
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'mensaje' =>  Yii::$app->params['errorExcepcion']];
            }
        }
    }  

    
    /*******************************************************************/
    /******************** EXPORTACION A EXCEL **************************/            
    public function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color) ));
    }  
    
    public function actionExportarExcel() {       
        try{
            
        
        if(Yii::$app->session->has('padronalumnos')){
             
            $data = Yii::$app->session->get('padronalumnos');
            $dataProvider = new \yii\data\SqlDataProvider([           
                'sql' => $data,  
                'pagination' =>false
            ]);
            $data = $dataProvider->getModels();
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                
                $this->cellColor($objPHPExcel, 'A1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'B1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'C1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'D1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'E1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'F1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'G1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'H1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'I1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'J1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'K1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'L1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'M1', 'F28A8C');
                
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'DNI');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'APELLIDO');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NOMBRE');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'FECHA NACIMIENTO');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'DOMICILIO');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'TELEFONO');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'NRO LEGAJO');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', 'ACTIVO');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'ESTABLECIMIENTO');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'DIVISION');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'BONIFICACION');
                $objPHPExcel->getActiveSheet()->setCellValue('L1', 'FAMILIA');
                $objPHPExcel->getActiveSheet()->setCellValue('M1', 'HIJO PROFESOR');
                
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
                    $columnaH = 'H' . $letrafilainicio1;
                    $columnaI = 'I' . $letrafilainicio1;
                    $columnaJ = 'J' . $letrafilainicio1;
                    $columnaK = 'K' . $letrafilainicio1;
                    $columnaL = 'L' . $letrafilainicio1;
                    $columnaM = 'M' . $letrafilainicio1;

                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $data[$i]["persona"]["nro_documento"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $data[$i]["persona"]["apellido"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, $data[$i]["persona"]["nombre"]);
                    $fecha_nacimiento = \app\models\Fecha::convertirFecha($data[$i]["persona"]["fecha_nacimiento"], 'Y-m-d','d-m-Y');
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, $fecha_nacimiento );
                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaE, $data[$i]["persona"]["miDomicilio"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaF, $data[$i]["persona"]["miTelContacto"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaG, $data[$i]["nro_legajo"]);
                
                    if ($data[$i]["activo"] == '1') {
                        //$this->cellColor($objPHPExcel, $columnaH, '89d4a3');
                        $objPHPExcel->getActiveSheet()->setCellValue($columnaH, "SI");
                    } else {
                       // $this->cellColor($objPHPExcel, $columnaH, '319cda');
                        $objPHPExcel->getActiveSheet()->setCellValue($columnaH, "NO");
                    }
                    
                    $miDivision = \app\models\DivisionEscolar::findOne($data[$i]["id_divisionescolar"]);
                    $miEstablecimiento = \app\models\Establecimiento::findOne($miDivision->id_establecimiento);
                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaI, $miEstablecimiento->nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaJ, $miDivision->nombre);
                    
                    $bonificaciones = \app\models\BonificacionAlumno::find()->where('id_alumno ='.$data[$i]["id"])->all();
                    $textoBonificaciones ='';
                    $lfcr = chr(10) . chr(13);
                    if(count($bonificaciones)>0){
                        foreach($bonificaciones as $one)
                            $textoBonificaciones.="\n" . $one->idBonificacion->descripcion;
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaK, $textoBonificaciones);
                    
                    $familia = \app\models\GrupoFamiliar::findOne($data[$i]["id_grupofamiliar"]);                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaL, $familia->apellidos . " - Folio: ".$familia->folio);
                    if ($data[$i]["hijo_profesor"] == '1') {                        
                        $objPHPExcel->getActiveSheet()->setCellValue($columnaM, "SI");
                    } else {                       
                        $objPHPExcel->getActiveSheet()->setCellValue($columnaM, "NO");
                    }

                    $i = $i + 1;
                    $letrafilainicio += 1;
                }  
                
                $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
                $nombre_archivo = "listadoAlumnos" . Yii::$app->user->id . ".xlsx";                                
                $ruta_archivo = $carp_cont . "/" . $nombre_archivo;
            
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($ruta_archivo);
                $url_pdf = \yii\helpers\Url::to(['alumno/down-padron', 'id' => $nombre_archivo]);
                
                Yii::$app->response->format = 'json';
                return ['result_error' => '0', 'result_texto' => $url_pdf];
                
            }else{
                Yii::$app->response->format = 'json';
                return ['result_error' => '1', 'message' => 'LISTADO VACIO'];
            }
        }
        }catch (\Exception $e) {
            Yii::error('exportar Alumnos '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
            return $this->redirect(['site/index']);            
        }  
    }

    public function actionDownPadron() {
        
        $name = $_GET["id"];        
        $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos                                       
        $ruta_archivo = $carp_cont . "/" . $name;
        
        if (is_file($ruta_archivo)) {
            $size = filesize($ruta_archivo);
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=listadoAlumnos.xlsx");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);
            readfile($ruta_archivo);
        }
    }     
}
