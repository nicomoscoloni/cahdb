<?php

namespace app\controllers;

use Yii;
use app\models\Cuentas;
use app\models\search\CuentasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CuentasController implements the CRUD actions for Cuentas model.
 */
class CuentasController extends Controller
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

    
    /**
     * Finds the Cuentas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cuentas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cuentas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    /****************************************************************/
    /****************************************************************/
    /**
     * Lists all CajaCash models.
     * @return mixed
     */
    public function actionListado()
    {
        $connection = Yii::$app->db;
        
        $modelCuentaColegio = \app\models\Cuentas::findOne(ConfController::CUENTA_COLEGIO);
        $modelCuentaPatagonia = \app\models\Cuentas::findOne(ConfController::CUENTA_PATAGONIA);
        
        return $this->render('cuentas', [           
            'modelCuentaColegio'=>$modelCuentaColegio,
            'modelCuentaPatagonia'=>$modelCuentaPatagonia,
            
        ]);
    }
    /****************************************************************/
    /****************************************************************/     
    public function actionResumenCuenta($id){
        try{
            $modelCuenta = $this->findModel($id);
            $searchModelMC = new \app\models\search\MovimientoCuentaSearch();
            $searchModelMC->id_cuenta = $id;
            $misMovimientos = $searchModelMC->searchResumenCuenta(Yii::$app->request->post());
            return $this->render('resumenCuenta',[
                        'modelCuenta'=>$modelCuenta, 
                        'searchModelMC'=>$searchModelMC,
                        'misMovimientos'=>$misMovimientos                            
                    ]);
        }
        catch (Exception $e){             
            Yii::$app->session->setFlash('error','ATENCION!!! <br /> Se Produjo un ERROR');
            $this->redirect(array('site/index'));
        }
    }//FIN actionResumenCuenta   
    
    
    
    
    
    /*******************************************************************/
    /*******************************************************************/            
    public function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color) ));
    }
        
    public function actionExportarExcel() {      
        if(Yii::$app->session->has('resumencuentas')){            
            $data = Yii::$app->session->get('resumencuentas');  
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
             
               
                $this->cellColor($objPHPExcel, 'A1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'B1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'C1', 'F28A8C');
                $this->cellColor($objPHPExcel, 'D1', 'F28A8C');
          
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'DETALLE');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'FECHA');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'TIPO MOVIMIENTO');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'MONTO');
                
                $letracolumnainicio = 'A';
                $letrafilainicio = 3;
                while ($i < $contador) {
                    $letrafilainicio1 = (string) $letrafilainicio;
                    $columnaA = 'A' . $letrafilainicio1;
                    $columnaB = 'B' . $letrafilainicio1;
                    $columnaC = 'C' . $letrafilainicio1;
                    $columnaD = 'D' . $letrafilainicio1;
                   
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $data[$i]["detalle_movimiento"]);
                    $fecharealizacion = \app\models\Fecha::convertirFecha($data[$i]["fecha_realizacion"], 'Y-m-d','d-m-Y');
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $fecharealizacion);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, $data[$i]["tipo_movimiento"]);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, "$ " .$data[$i]["importe"]);
                   
                    $i = $i + 1;
                    $letrafilainicio += 1;
                }  
                
                $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
                $nombre_archivo = "listadoAbagados". Yii::$app->user->id .".xlsx";                                
                $ruta_archivo = $carp_cont . "/" . $nombre_archivo;
            
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($ruta_archivo);
                $url_pdf = \yii\helpers\Url::to(['cuentas/down-padron', 'id' => $nombre_archivo]);
                
                Yii::$app->response->format = 'json';
                return ['result_error' => '0', 'result_texto' => $url_pdf];
                
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
            header("Content-Disposition: attachment; filename=ListadoAbogados.xlsx");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);
            readfile($ruta_archivo);
        }
    }
    
    
    
}
