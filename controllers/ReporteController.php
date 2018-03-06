<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use app\models\Persona;
use app\models\Alumno;
use app\models\search\AlumnoSearch;


/**
 * BonificacionAlumnoController implements the CRUD actions for BonificacionAlumno model.
 */
class ReporteController extends Controller
{
    public function actionBonificacionesAlumno(){   
        try{
            $modelPersona =  new Persona();
            $modelPersona->load(Yii::$app->request->queryParams);
        
            $searchModelBonificacion = new \app\models\search\BonificacionAlumnoSearch();
            $dataProviderBonificacion = $searchModelBonificacion->search(Yii::$app->request->queryParams,$modelPersona);
        }catch (\Exception $e) {
            Yii::error('Reporte Bonificaciones de alumnos '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);                      
        }    
        return $this->render('bonificacionesalumno',[
            'searchModel' => $searchModelBonificacion,
            'dataProvider' => $dataProviderBonificacion,
            'modelPersona'=> $modelPersona,
        ]);
    }
    
    public function actionDownloadVideo($name){
        $carp_cont = Yii::getAlias('@webroot') . "/videos";
        $filename = $name.'.mp4';     
        $ruta_archivo = $carp_cont."/".$filename; 
        
        
        if (is_file($ruta_archivo)) {
           $size = filesize($ruta_archivo);   
               
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Content-Description: File Download');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: max-age=0');      
                readfile($ruta_archivo);
                exit;
        }
    }
        
    public function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color) ));
    }  
    
    public function actionExportarExcelAlumnosbonificaciones() {  
        ini_set('memory_limit', -1);
        set_time_limit(0);
        try{
        
        if(Yii::$app->session->has('alumnosconbonificaciones')){             
            $data = Yii::$app->session->get('alumnosconbonificaciones');
            
            $model = \app\models\BonificacionAlumno::findBySql($data);
            
            $dataProviderSession = new \yii\data\ActiveDataProvider([
                'query' => $model,           
                'pagination' => false
            ]);
            
            $data = $dataProviderSession->getModels();
            
            
            
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
           
                
                $this->cellColor($objPHPExcel, 'A1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'B1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'C1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'D1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'E1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'F1', 'F28A8C');
               
                
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'DNI');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'APELLIDO');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NOMBRE');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'FAMILIA');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'FAMILIA');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'BONIFICACION');
                
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

                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $data[$i]["alumno"]["persona"]["nro_documento"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $data[$i]["alumno"]["persona"]["apellido"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, $data[$i]["alumno"]["persona"]["nombre"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, $data[$i]["alumno"]["grupofamiliar"]["apellidos"]);
                    
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaF, $data[$i]["bonificacion"]["descripcion"] . " ".$data[$i]["bonificacion"]["valor"]);
                    
                    
                    
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

