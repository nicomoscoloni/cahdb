<?php

namespace app\controllers;

use Yii;
use app\models\ServicioOfrecido;
use app\models\search\ServicioOfrecidoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ServicioAlumno;
use app\models\search\ServicioAlumnoSearch;
use app\models\Alumno;
use app\models\search\AlumnoSearch;

/**
 * ServicioOfrecidoController implements the CRUD actions for ServicioOfrecido model.
 */
class ServicioOfrecidoController extends Controller
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
                        'actions' => ['admin','delete','view','alta','update'],
                        'allow' => true,
                        'roles' => ['abmlServicioOfrecido'],
                    ],
                    [     
                        'actions' => ['devengar-servicio','eliminar-devengamiento'],
                        'allow' => true,       
                        'roles' => ['devengarServicioOfrecido'],
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
    
    /******************************************************************/
    /**
     * Lists all ServicioOfrecido models.
     * @return mixed
     */
    public function actionAdmin()
    {
        try{
            $searchModel = new ServicioOfrecidoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }catch (\Exception $e) {
            Yii::error('Administración Servicios Ofrecidos '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
        }   
        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /******************************************************************/
    /**
     * Deletes an existing ServicioOfrecido model.
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
            Yii::error('Delete Servicios Ofrecidos '.$e);
            Yii::$app->session->setFlash('error', 'No se puede eliminar un Servicio asociado a un Establecimiento.');
            return $this->redirect(['admin']);                        
        }
    }

    /******************************************************************/
    /**
     * Displays a single ServicioOfrecido model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        try{
            $modelServicio = $this->findModel($id);

            $searchModelSerAlumnos = new \app\models\search\ServicioAlumnoSearch();
            $searchModelSerAlumnos->id_servicio = $id;
            $dataProviderSerAlumnos = $searchModelSerAlumnos->search(Yii::$app->request->get());
        }catch (\Exception $e) {
            Yii::error('View Servicios Ofrecidos '.$e);              
            Yii::$app->session->setFlash('error',Yii::$app->params['operacionFallida']);
        }
        return $this->render('view', [
            'model' => $modelServicio,
            'searchModelSerAlumnos'=>$searchModelSerAlumnos,
            'dataProviderSerAlumnos'=>$dataProviderSerAlumnos
        ]);
    }

    /******************************************************************/
    /**
     * Creates a new ServicioOfrecido model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAlta()
    {
        $transaction = Yii::$app->db->beginTransaction();        
        try{
            $model = new ServicioOfrecido();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success',Yii::$app->params['cargaCorrecta']);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }           
        }catch (\Exception $e) {
            Yii::error('Alta Servicios Ofrecidos '.$e);
            $transaction->rollBack();            
            Yii::$app->session->setFlash('error',Yii::$app->params['operacionFallida']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ServicioOfrecido model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try{
            $model = $this->findModel($id);
            $montoviejo = $model->importe;
            $montoviejohijo = $model->importe_hijoprofesor;
            
            //sino se encuentra devengado procedemos a la actualizacion            
            if ($model->load(Yii::$app->request->post())) {
                $servicioDevengado = \app\models\ServicioAlumno::find()->joinWith(['servicio so'])->where('so.id='.$id)->all();
                
                if (($model->importe!=$montoviejo || $model->importe_hijoprofesor!=$montoviejohijo) && (count($servicioDevengado)>0))                
                    Yii::$app->session->setFlash('error','No se puede modificar el valor a un Servicio que ya a sido devengado.');
                elseif ($model->save()){
                    Yii::$app->session->setFlash('success',Yii::$app->params['actualizacionCorrecta']);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }   
            } 
        }catch (\Exception $e) {
            Yii::error('Actualización Servicios Ofrecidos '.$e);
            $transaction->rollBack(); 
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Finds the ServicioOfrecido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServicioOfrecido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServicioOfrecido::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**********************************************************************/
    /**********************************************************************/
    /******************************************************************/
    /******************* DEVENGAMIENTO DE SERVICIOS *******************/ 
    /**
     * Funcion que se encarga de devengar la matricula del periodo
     * activo a todos los abogados matriculados en el sistema.
     * 
     * Para esto se encarga de buscar la proxima matricula a abonar,
     * y para esta la devenga a todos los abogados matriculados,
     * asignandole dicho servicio(de matricula a pagar)
     */
    public function actionDevengarServicio($id=null){
        ini_set('memory_limit', '-1');
        ini_set('set_time_limite', '300');
        ini_set('max_execution_time', 300);
        
        try{ 
            $connection= \Yii::$app->db;              
            $transaction = \Yii::$app->db->beginTransaction();

                $sql_servicio = "SELECT 
                            so.id as idservicioofrecido, 
                            se.id as idservicio, 
                            so.importe as montoservicio, 
                            so.importe_hijoprofesor as montoservicio_hijoprofesor , 
                            se.id_divisionescolar as divisionescolar,
                            a.id as idalumno,
                            a.id_divisionescolar as divisionescolar,
                            a.hijo_profesor as hijo_profesor
                        FROM alumno a
                        INNER JOIN division_escolar AS de ON (a.id_divisionescolar=de.id)
                        INNER JOIN  servicio_establecimiento AS se ON (se.id_divisionescolar = de.id)
                        INNER JOIN servicio_ofrecido AS so  ON (so.id = se.id_servicio)      
                        LEFT JOIN servicio_alumno AS sa ON (sa.id_servicio = so.id and sa.id_alumno = a.id)";
            
                $where = 'WHERE sa.id is null';
            if($id !== null){
                $where.=" and (so.devengamiento_automatico = '1')  and so.id=".$id;    
            }/*else{
                $where.=" and (so.devengamiento_automatico = '1')  AND ((fecha_vencimiento <=  DATE_ADD(NOW(), INTERVAL + 1 MONTH)))";
            }  */          
            $sql_servicio.=$where;            
            $valid = true;

            $command_servicios  =  $connection->createCommand($sql_servicio);
            $servicios = $command_servicios->queryAll(); 
            
            foreach ($servicios as $servicio){
                // chequeamos si ya se devengo la matricula                   
                $modelServicio = new \app\models\ServicioAlumno();
                $modelServicio->id_alumno = $servicio['idalumno'];
                $modelServicio->id_servicio = $id;// $servicio['idservicioofrecido'];
                $modelServicio->estado = ConfController::estadoSA_ABIERTA;

                if( $servicio['hijo_profesor']=='0')
                    $modelServicio->importe_servicio = $servicio['montoservicio'];
                else
                    $modelServicio->importe_servicio = $servicio['montoservicio_hijoprofesor'];

                $modelServicio->importe_descuento =0;
                $modelServicio->importe_abonado =0;                        
                $modelServicio->fecha_otorgamiento = date('Y-m-d');
                $valid = $valid && $modelServicio->save();

                $descuentosAlumno = \app\models\BonificacionAlumno::find()->where('id_alumno='. $servicio['idalumno'])->all();
                $total_descuentos = 0;
                if(!empty($descuentosAlumno)){
                    foreach($descuentosAlumno as $descuento){
                        $modelDescuentoServicio = new \app\models\BonificacionServicioAlumno();
                        $modelDescuentoServicio->id_bonificacion = $descuento->id_bonificacion;
                        $bonificacion = \app\models\CategoriaBonificacion::findOne($descuento->id_bonificacion); 
                        $modelDescuentoServicio->id_servicioalumno = $modelServicio->id;
                        $total_descuentos += $bonificacion->valor;
                        $valid = $valid && $modelDescuentoServicio->save();
                    }
                    $modelServicio->importe_descuento = ( $modelServicio->importe_servicio * $total_descuentos) / 100;
                    $valid = $valid && $modelServicio->save();
                }  
            }            

            if ($valid){
                $transaction->commit();
                Yii::$app->response->format = 'json';
                return ['error' => '0', 'resultado' => 'EXITO'];
            }else{
                $transaction->rollback();
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'resultado' => 'FRACASO'];
            }                
        }
        catch(Exception $e)
        {
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'resultado' => 'FRACASO'];
            $transaction->rollback();               
        }
    } //fin DevengamientoMatriculas

    public function actionEliminarDevengamiento($id=null){
        try{ 
            $connection= \Yii::$app->db;              
            $transaction = \Yii::$app->db->beginTransaction();

            $estado = 'A';
            $command_servicios = Yii::$app->db->createCommand("
                DELETE  FROM servicio_alumno   
                    WHERE importe_abonado=0 and estado='".ConfController::estadoSA_ABIERTA."'  and id_servicio= $id");
            
            $command_bonificaciones = Yii::$app->db->createCommand("
                DELETE FROM bonificacion_servicio_alumno
                WHERE id_servicioalumno in (SELECT id FROM `servicio_alumno` WHERE id_servicio =". $id ." and estado like '%A%' )");
            
            $bonificaciones_eliminadas = $command_bonificaciones->execute(); 
            $servicios_eliminados = $command_servicios->execute(); 
          
            if ($servicios_eliminados>0){
                $transaction->commit();
                Yii::$app->response->format = 'json';
                return ['error' => '0', 'resultado' => 'EXITO'];
            }else{
                $transaction->rollback();
                Yii::$app->response->format = 'json';
                return ['error' => '1', 'resultado' => 'FRACASO'];
            }                
        }
        catch(\Exception $e)
        {
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'resultado' => 'FRACASO'];
            $transaction->rollback();               
        }
    }
    
}