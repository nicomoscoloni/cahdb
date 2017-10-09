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
                        //'roles' => ['abmlServicioOfrecido'],
                    ],
                    [     
                        'actions' => ['devengar-servicio','eliminar-devengamiento'],
                        'allow' => true,       
                        //'roles' => ['devengarServicioOfrecido'],
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

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }catch (\Exception $e) {
            Yii::error('Administración Servicios Ofrecidos '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
            return $this->redirect(['site/index']);            
        }        
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
            $searchModelSerAlumnos->servicio_ofrecido = $id;
            $dataProviderSerAlumnos = $searchModelSerAlumnos->search(Yii::$app->request->post());

            return $this->render('view', [
                'model' => $modelServicio,
                'searchModelSerAlumnos'=>$searchModelSerAlumnos,
                'dataProviderSerAlumnos'=>$dataProviderSerAlumnos
            ]);    
        }catch (\Exception $e) {
            Yii::error('View Servicios Ofrecidos '.$e);              
            Yii::$app->session->setFlash('error',Yii::$app->params['operacionFallida']);
            return $this->redirect(['admin']);                        
        }
        
        
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
            
            return $this->render('create', [
                'model' => $model,
            ]);            
        }catch (\Exception $e) {
            Yii::error('Alta Servicios Ofrecidos '.$e);
            $transaction->rollBack();            
            Yii::$app->session->setFlash('error',Yii::$app->params['operacionFallida']);
            return $this->redirect(['admin']);                        
        }
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
                $servicioDevengado = \app\models\ServicioAlumno::find()->joinWith(['idServicio se'])->where('se.id='.$id)->all();
                
                if (($model->importe!=$montoviejo || $model->importe_hijoprofesor!=$montoviejohijo) && (count($servicioDevengado)>0))                
                    Yii::$app->session->setFlash('error','No se puede modificar el valor a un Servicio que ya a sido devengado.');
                elseif ($model->save()){
                    Yii::$app->session->setFlash('success',Yii::$app->params['actualizacionCorrecta']);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }   
            } 
            
            return $this->render('update', [
                'model' => $model,
            ]);            
        }catch (\Exception $e) {
            Yii::error('Actualización Servicios Ofrecidos '.$e);
            $transaction->rollBack(); 
            Yii::$app->session->setFlash('error', Yii::$app->params['operacionFallida']);
            return $this->redirect(['admin']);
        }
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
    private function esServicioDevengado($idAlumno, $idServicio){        
        $condition = 'id_servicio='.$idServicio.' and id_alumno='.$idAlumno;
        $existe = \app\models\ServicioAlumno::find()->where($condition)->one();

        if (!empty($existe))
            return true;
        else
            return false; 
    }//fin esServicioDevengado
        
        
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

            $sql_servicio = "SELECT so.id as idservicioofrecido, se.id as idservicio, so.monto as montoservicio, so.monto_hijoprofesor as montoservicio_hijoprofesor , se.id_divisionescolar as divisionescolar 
                                FROM servicio_ofrecido AS so
                                INNER JOIN  servicio_establecimiento AS se ON (so.id = se.id_servicio)";
            
            $where = " WHERE (so.devengamiento_automatico = '1')  AND ((fecha_vencimiento <=  DATE_ADD(NOW(), INTERVAL + 1 MONTH)))";
            if($id !== null){
                $where .=" and so.id=".$id;    
            }
                
            
            $sql_servicio .= $where;
            
            $valid = true;

            $command_servicios  =  $connection->createCommand($sql_servicio);
            $servicios = $command_servicios->queryAll(); 
            
                foreach ($servicios as $servicio){
                
                    $alumnos = Alumno::find()->where('activo=\'1\' and id_divisionescolar='.(int)$servicio['divisionescolar'])->all();
                  
                    if(!empty($alumnos)){
                        foreach($alumnos as $alumno){
                        // chequeamos si ya se devengo la matricula                   
                        if ($valid && ($this->esServicioDevengado($alumno->id, $servicio['idservicio'])==FALSE) ){
                            $modelServicio = new \app\models\ServicioAlumno();
                            $modelServicio->id_alumno = $alumno->id;
                            $modelServicio->id_servicio = $servicio['idservicio'];
                            
                            $modelServicio->estado = 'A';
                            
                            if($alumno->hijo_profesor=='0')
                                $modelServicio->importe_servicio = $servicio['montoservicio'];
                            else
                                $modelServicio->importe_servicio = $servicio['montoservicio_hijoprofesor'];
                            
                            $modelServicio->importe_descuento =0;
                            $modelServicio->importe_abonado =0;                        
                            $modelServicio->fecha_otorgamiento = date('Y-m-d');
                            $valid = $valid && $modelServicio->save();

                           
                                $descuentosAlumno = \app\models\BonificacionAlumno::find()->where('id_alumno='.$alumno->id)->all();
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
                    } // fin foreach  Alumnos
                    }
            } //fin foreach Servicios

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
             DELETE sa.* FROM servicio_alumno as sa  
                    INNER JOIN servicio_establecimiento as se ON (sa.id_servicio = se.id) 
                    INNER JOIN servicio_ofrecido as so ON (so.id = se.id_servicio) 
                    WHERE sa.estado='A'  and so.id=$id");
            
            $servicios = $command_servicios->execute(); 
          
            if ($servicios>0){
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
    }
    
}