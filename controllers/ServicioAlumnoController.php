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
    
    
        public function actionRemover($id)
    {
        $transaction = Yii::$app->db->beginTransaction(); 
        try{
            $model = $this->findModel($id);
            
            if ( $model->delete()){
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
            Yii::error('Remover Servicio Alumnos '.$e);
            $transaction->rollBack();            
            Yii::$app->response->format = 'json';
            return ['error' => '1', 'mensaje' =>  'Error al activar al Alumno!!!'];            
        }
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
    
   /*******************************************************************/
    /******************** EXPORTACION A EXCEL **************************/            
    public function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color) ));
    }  
    
    public function actionExportarExcel() {       
        
        if(Yii::$app->session->has('serviciosalumnos')){
            
            $data = Yii::$app->session->get('serviciosalumnos'); 
            
            $model = ServicioAlumno::findBySql($data);
            
            $dataProviderSession = new \yii\data\ActiveDataProvider([
                'query' => $model,           
                'pagination' => false
            ]);
            
            $data = $dataProviderSession->getModels();
            
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
                
                
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'DNI');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'APELLIDO');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NOMBRE');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'ESTABLECIMIENTO');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'DIVISION');
                
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'SERVICIO');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'IMPORTE SERVICIO');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', 'IMPORTE DESCUENTO');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'IMPORTE ABONAR');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'IMPORTE ABONADO');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'ESTADO');
                
                
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

                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $data[$i]["miAlumno"]["miPersona"]["nro_documento"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $data[$i]["miAlumno"]["miPersona"]["apellido"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, $data[$i]["miAlumno"]["miPersona"]["nombre"]);
                    
                    $miDivision = \app\models\DivisionEscolar::findOne($data[$i]["miAlumno"]["id_divisionescolar"]);
                    $miEstablecimiento = \app\models\Establecimiento::findOne($miDivision->id_establecimiento);
                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, $miEstablecimiento->nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaE, $miDivision->nombre);
                    

                    //$objPHPExcel->getActiveSheet()->setCellValue($columnaF, $data[$i]["miServicio"]["importe_servicio"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaG, $data[$i]["importe_servicio"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaH, $data[$i]["importe_descuento"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaI, $data[$i]["importeAbonar"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaJ, $data[$i]["importe_abonado"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaK, $data[$i]["detalleEstadoExcel"]);
                    
                    
                    
                    $i = $i + 1;
                    $letrafilainicio += 1;
                }  
                
                $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
                $nombre_archivo = "listadoAlumnos" . Yii::$app->user->id . ".xlsx";                                
                $ruta_archivo = $carp_cont . "/" . $nombre_archivo;
            
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($ruta_archivo);
                $url_pdf = \yii\helpers\Url::to(['servicio-alumno/down-padron', 'id' => $nombre_archivo]);
                
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
            header("Content-Disposition: attachment; filename=serviciosAlumnos.xlsx");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);
            readfile($ruta_archivo);
        }
    }         
 
}
