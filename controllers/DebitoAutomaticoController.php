<?php

namespace app\controllers;

use Yii;
use app\models\DebitoAutomatico;
use app\models\search\DebitoAutomaticoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * DebitoAutomaticoController implements the CRUD actions for DebitoAutomatico model.
 */
class DebitoAutomaticoController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors(){
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [     
                        'actions' => ['administrar','view','generar'],
                        'allow' => true,
                        'roles' => ['gestionarDebitoAutomatico'],
                    ], 
                    [     
                        'actions' => ['procesar'],
                        'allow' => true,
                        'roles' => ['gestionarDebitoAutomatico'],
                    ],
                    [     
                        'actions' => ['descargar-archivo-envio','descarga-txt','descarga-excel','convertir-a-excel'],
                        'allow' => true,
                        'roles' => ['gestionarDebitoAutomatico'],
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
    
    /*******************************************************/
    /*******************************************************/
    /**
     * Finds the DebitoAutomatico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DebitoAutomatico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if (($model = DebitoAutomatico::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /****************************************************************/
    /****************************************************************/
    /**
     * Lists all DebitoAutomatico models.
     * @return mixed
     */
    public function actionAdministrar(){
        try{
            $searchModel = new DebitoAutomaticoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } catch (Exception $ex) {
            Yii::error('Administración Debitos Automatico '.$e);
            Yii::$app->session->setFlash('error', Yii::$app->params['errorExcepcion']);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    } 
    
    /****************************************************************/
    /*****************************************************************/
    public function actionView($id){
        try{
            $model = $this->findModel($id);            
           
            $searchItemsDebitos = new \app\models\search\ServicioDebitoAutomaticoSearch();
            $searchItemsDebitos->id_debitoautomatico = $model->id;             
            $dataMisDebitos = $searchItemsDebitos->search(Yii::$app->request->queryParams);  
        }
        catch(Exception $e)
        {
            Yii::app()->user->setFlash('error','ERROR SEVERO EN LA CARGA DEL ARCHIVO!!!');
            $this->redirect(array('view'));   
        }
        
        return $this->render('view',[
            'model'=>$model,
            'searchItemsDebito' => $searchItemsDebitos,
            'dataMisDebitos' => $dataMisDebitos,
        ]);
    }
    
    /*****************************************************************/
    /*
     * Inicio Generacion de archivos envio BANCO
     */
    /*****************************************************************/
    /*
     * Funcion General encargada de generarun nuevo archivo a enviar al BANCO.
     * Se provee d eotras funciones propias encragadas de generar
     * archivos especificos
     */
    public function actionGenerar(){
        try{
            $transaction = Yii::$app->db->beginTransaction(); 
            
            $model = new DebitoAutomatico();
            $model->procesado = '0';
            $model->saldo_entrante = '0';
            $model->saldo_enviado = '0';
            $model->banco ='PATAGONIA';
            $model->fecha_creacion =date('Y-m-d');    
            
            if($model->load(Yii::$app->request->post()) && $model->save()){
                
                $result = $this->generarArchivoDebito($model->id);                
                if($result == TRUE){
                    $transaction->commit();
                    Yii::$app->session->setFlash('ok', 'Se genero con exito ');
                    return $this->redirect(['view', 'id' => $model->id]);
                }else
                {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'No se puede generar el archivo. No existen servicios en el periodo mencionado.');
                }
            }   
        }
        catch (Exception $e){
            Yii::$app->session->setFlash('error','ATENCION!!! <br /> Se Produjo un error severo');
            $this->redirect(['admin']);
        }
        
        return $this->render('create',[
            'model'=>$model,                        
        ]);
    }    
    
    private function generarArchivoDebito($id){
        $model = DebitoAutomatico::findOne($id);
        if(!$model)
          return false;
        else{
            //segun tipo de archivo a generar, llamamos a sus respetivos metodos de generacion
            if($model->tipo_archivo == 'TC'){                      
                return $this->generaArchivoPatagoniaTC($model->id);                 
            }
            if($model->tipo_archivo == 'CBU'){                      
                return $this->generaArchivoPatagoniaCBU($model->id);                 
            }
        }
    }
    
    
    /****************************************************************/
    /************ DEBITOS TC BANCO PATAGONIA ************************/
    /****************************************************************/  
    public function generaArchivoPatagoniaTC($idArchivo){          
        ini_set('memory_limit', -1);
        set_time_limit(240);
        
        $transaction = Yii::$app->db->beginTransaction();
        
        $modelArchivo = $this->findModel($idArchivo);
       
        $periodoIni = $modelArchivo->inicio_periodo;
        $periodoFin = $modelArchivo->fin_periodo;
        $fechaVencimiento = $modelArchivo->fecha_debito;
        
        try{
            $sql = "
            SELECT * FROM (
                (
                    SELECT 
                        a.nrofamilia as nrofamilia, 
                        a.nrotarjeta, 
                        ccp.id as idservicio, 
                        (ccp.monto - ccp.importe_abonado) as monto, 
                        'CUOTAS CONVENIO PAGO' as tiposervicio
                    FROM `cuota_convenio_pago` ccp 
                     INNER JOIN convenio_pago cp ON (cp.id = ccp.id_conveniopago)
                     INNER JOIN ( 
                        SELECT a.id as idalumno, f.id as nrofamilia, f.nro_tarjetacredito as nrotarjeta 
                         FROM alumno a 
                         INNER JOIN grupo_familiar f ON (f.id = a.id_grupofamiliar) 
                          WHERE (f.id_pago_asociado = 5) 
                     ) a ON (a.nrofamilia = cp.id_familia)
                     WHERE (ccp.estado='A') and (ccp.fecha_establecida>='". $periodoIni ."' and ccp.fecha_establecida<='". $periodoFin ."' )
                ) 
                UNION
                (
                    SELECT a.nrofamilia as nrofamilia, a.nrotarjeta, sa.id as idservicio, 
                            (sa.importe_servicio - sa.importe_descuento - sa.importe_abonado) as monto, 
                            'SERVICIOS' as tiposervicio
                        FROM servicio_alumno sa 
                        INNER JOIN ( 
                            SELECT a.id as idalumno, f.id as nrofamilia, f.nro_tarjetacredito as nrotarjeta 
                             FROM alumno a INNER JOIN grupo_familiar f ON (f.id = a.id_grupofamiliar) 
                              WHERE (f.id_pago_asociado = 5) 
                        ) a ON (a.idalumno = sa.id_alumno) 
                        
                        INNER JOIN servicio_ofrecido so ON (so.id = sa.id_servicio)                     
                        INNER JOIN categoria_servicio_ofrecido cts ON (cts.id = so.id_tiposervicio) 
                     WHERE (sa.estado = 'A') 
                     and ((so.fecha_vencimiento >= '".$periodoIni."') and (so.fecha_vencimiento <= '".$periodoFin."'))
                ) 
            ) as D
            ORDER BY D.nrofamilia";

            $connection = $connection = Yii::$app->db;        
            $command = $connection->createCommand($sql);
            $result = $command->queryAll();
            
            

            if(!empty($result)){
                $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/tc/generados";
                $filename = 'debitos-'. $modelArchivo->id .'.txt';
                $filename_1 = $filename;
                $filename = $carp_cont."/".$filename;          
                
                $contenido="";     
                
                /////HEADER  - encabezado
                $encabezado="";
                $encabezado.="0DEBLIQC ";
                $encabezado.="0025255886"; 
                $encabezado.="900000    ";
                $encabezado.=date('Ymdhm');
                $encabezado.="0                                                         *";
                $encabezado.="\r\n"; 

                $contenido.=$encabezado;
                
                $saldo_total = 0;                   
                $cantidad = 0;
                $procesa = true;   
                
                //variables que mantiene la cantidad y totales de servicios de cada 
                //familia en cada linea, para cada familia solo se manda un regln; no se puede detallar cada servicio por separado
                $nroServicioFamilia = 1;
                $nroFamiliaAnterior = $result[0]['nrofamilia'];
                $nrotarjetaAnterior = $result[0]['nrotarjeta'];
                $totalMontoFamilia=0;
                
                foreach($result as $row){                  
                    if($procesa == TRUE){ 
                        $cantidad +=1;

                        $nrofamilia = $row['nrofamilia'];
                        $nrotarjeta = $row['nrotarjeta'];
                        $monto = $row['monto'];
                        $saldo_total = $saldo_total + $monto;  

                        if($nroFamiliaAnterior!=$nrofamilia){
                            $contenido.= $this->devolverLinea_PATAGONIA_TC($nroFamiliaAnterior, $nrotarjeta, $cantidad, $totalMontoFamilia, $fechaVencimiento);  
                            $nroServicioFamilia = 1;
                            $nroFamiliaAnterior = $nrofamilia;
                            $totalMontoFamilia=$monto;
                        }else
                          $totalMontoFamilia +=  $monto;


                        $servicio_da = new \app\models\ServicioDebitoAutomatico();
                        $servicio_da->id_debitoautomatico = $modelArchivo->id;
                        $servicio_da->id_servicio = $row['idservicio'];
                        $servicio_da->tiposervicio = $row['tiposervicio'];
                        $servicio_da->linea = 'FAMILIA '.$nrofamilia .' - MATRICULA ' .$nroServicioFamilia;

                        if($row['tiposervicio']=='SERVICIOS'){
                            $miServicio = \app\models\ServicioAlumno::findOne($row['idservicio']);
                            $miServicio->estado = 'DA';
                            $procesa = $servicio_da->save() && $miServicio->save();    
                        }else
                        if($row['tiposervicio']=='CUOTAS CONVENIO PAGO'){
                            $miServicio = \app\models\CuotaConvenioPago::findOne($row['idservicio']);
                            $miServicio->estado = 'DA';
                            $procesa = $servicio_da->save() && $miServicio->save();    
                        } 
                    }                    
                    
                }
                $contenido.= $this->devolverLinea_PATAGONIA_TC($nrofamilia, $nrotarjeta, $cantidad, $totalMontoFamilia, $fechaVencimiento);                           

                $modelArchivo->saldo_enviado = $saldo_total;
                
                $pie="9DEBLIQC ";
                $pie.="0025255886"; 
                $pie.="900000    ";
                $pie.=date('Ymdhm');
                $pie.=str_pad($cantidad,7,"0",STR_PAD_LEFT);
                $saldo_total=number_format($saldo_total, 2);
                $saldo_total = str_replace(",","",str_replace(".","",$saldo_total));
                $pie.=str_pad($saldo_total,15,"0",STR_PAD_LEFT);
                $pie.="                                    ";
                $pie.="*";
                $pie.="\r\n";

                $contenido.=$pie;
                
                $modelArchivo->registros_enviados = $cantidad;                
                $modelArchivo->saldo_entrante = 0;             
                $procesa = $procesa && $modelArchivo->save();

                if($procesa){                    
                    if (!$handle = fopen("$filename", "w")) { 
                        $se_genero = false;  
                        return false;
                        exit;
                    }else {
                        ftruncate($handle,filesize("$filename"));
                    }

                    $link = "";
                    if (fwrite($handle, $contenido) === FALSE){
                        $se_genero = false;
                        return false;
                        exit;
                    }else{ 
                        fclose($handle);	 
                        $se_genero = true; 
                        $archivo  = $modelArchivo->id;
                    }

                    $transaction->commit();
                    //colocar codigo para avisar por correo

                    return true;           
                } //fin del procesa == TRUE
            }else     
            {
                return -1;
            }
        }catch(CDbException $e)
        {
             return false;
        }           
    } //actionGenArc_Patagonia_TC       
    
    private function devolverLinea_PATAGONIA_TC($nrofamilia, $nrotarjeta, $cantidad, $monto, $fecha_vencimiento_pago){
        $contenido='';
        $contenido.="1";
        $nrotarjeta = str_replace("","0",$nrotarjeta);
        $contenido.=str_pad($nrotarjeta,16," ",STR_PAD_LEFT);                
        $contenido.="   ";
        $contenido.=str_pad($nrofamilia,8,"0",STR_PAD_LEFT); 
        $fechavencimiento = $fecha_vencimiento_pago;
        $fechavencimiento = str_replace("-","",$fechavencimiento);            
        $contenido.=str_replace("-","",$fechavencimiento); //formato de la fecha yyyymmdd
        $contenido.="0005";    
        $montoCuota = number_format($monto, 2);
        $montoCuota = str_replace(",","",str_replace(".","",$montoCuota));            
        $contenido.=str_pad($montoCuota,15,"0",STR_PAD_LEFT); 
        $identificador = 'F99999F'.  str_pad($nrofamilia,8,"0",STR_PAD_LEFT);
        $contenido.=   $identificador;       
        $contenido.="E"; 
        $contenido.="  ";
        $contenido.="                          ";
        $contenido.="*";
        $contenido.="\r\n";
        return $contenido;
    }  
    
    /****************************************************************/
    /************ DEBITOS CBU BANCO PATAGONIA ***********************/
    /****************************************************************/    
    public function generaArchivoPatagoniaCBU($idArchivo){          
        ini_set('memory_limit', -1);
        set_time_limit(240);
        set_time_limit(-1);
        
        $modelArchivo = $this->findModel($idArchivo);
       
        $periodoIni = $modelArchivo->inicio_periodo;
        $periodoFin = $modelArchivo->fin_periodo;
        $fechaVencimiento = $modelArchivo->fecha_debito;
        
        $fechaVencimientoLinea = \app\models\Fecha::convertirFecha($modelArchivo->fecha_debito,"Y-m-d","d-m-Y");
                
        try{
            $sql = "
            SELECT * FROM (
                (
                    SELECT 
                        a.nrofamilia as nrofamilia, 
                        a.cbu, 
                        ccp.id as idservicio, 
                        (ccp.monto - ccp.importe_abonado) as monto, 
                        'CUOTAS CONVENIO PAGO' as tiposervicio
                    FROM `cuota_convenio_pago` ccp 
                     INNER JOIN convenio_pago cp ON (cp.id = ccp.id_conveniopago)
                     INNER JOIN ( 
                        SELECT a.id as idalumno, f.id as nrofamilia, f.cbu_cuenta as cbu 
                         FROM alumno a 
                         INNER JOIN grupo_familiar f ON (f.id = a.id_grupofamiliar) 
                          WHERE (f.id_pago_asociado = 4) 
                     ) a ON (a.nrofamilia = cp.id_familia)
                     WHERE (ccp.estado='A') and (ccp.fecha_establecida>='". $periodoIni ."' and ccp.fecha_establecida<='". $periodoFin ."' )
                ) 
                UNION
                (
                    SELECT a.nrofamilia as nrofamilia, a.cbu, sa.id as idservicio, 
                            (sa.importe_servicio - sa.importe_descuento - sa.importe_abonado) as monto, 
                            'SERVICIOS' as tiposervicio
                        FROM servicio_alumno sa 
                        INNER JOIN ( 
                            SELECT a.id as idalumno, f.id as nrofamilia, f.cbu_cuenta as cbu 
                             FROM alumno a INNER JOIN grupo_familiar f ON (f.id = a.id_grupofamiliar) 
                              WHERE (f.id_pago_asociado = 4)
                        ) a ON (a.idalumno = sa.id_alumno) 
                       
                        INNER JOIN servicio_ofrecido so ON (so.id = sa.id_servicio)
                    WHERE (sa.estado = 'A')  
                       and ((so.fecha_vencimiento >= '".$periodoIni."') and (so.fecha_vencimiento <= '".$periodoFin."'))
                ) 
            ) as D
            ORDER BY D.nrofamilia";
       
            $connection = Yii::$app->db;        
            $command = $connection->createCommand($sql);
            
            $result = $command->queryAll();            
            
            if(!empty($result)){
                $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/cbu/generados";
                $filename = 'debitos-'. $modelArchivo->id .'.txt';                
                $filename = $carp_cont."/".$filename;          
                
                $contenido="";
                
                /////HEADER  - encabezado
                $encabezado="";
                $encabezado.="H30630291727";
                $encabezado.="CUOTA     ";
                $encabezado.='618';
                $encabezado.= str_replace("/","",date('d/m/Y'));
                $encabezado.='            ';
                $encabezado.='COLEGIO VECCHI                     ';
                $encabezado.=str_pad('',120," ",STR_PAD_LEFT);
                $encabezado.="\r\n"; 

                $contenido.=$encabezado;
                
                $saldo_total = 0;                   
                $cantidad = 0;
                $procesa = true;

                $nroServicioFamilia = 1;
                $nroFamiliaAnterior = 0;
            
                foreach($result as $row){                  
                    if($procesa == TRUE){ 
                        $cantidad +=1;
                        
                        $nrofamilia = $row['nrofamilia'];
                        $cbu = $row['cbu'];
                        $monto = $row['monto'];
                        $saldo_total = $saldo_total + $monto;  

                        if($nrofamilia!=$nroFamiliaAnterior){
                            $nroServicioFamilia = 1;
                            $nroFamiliaAnterior=$nrofamilia;    
                        }else{
                           $nroServicioFamilia+=1;                            
                        }
                        
                        $contenido.= $this->devolverLinea_PATAGONIA_CBU($nrofamilia, $cbu, $cantidad, $monto, $fechaVencimientoLinea, $nroServicioFamilia); 

                        $servicio_da = new \app\models\ServicioDebitoAutomatico();
                        $servicio_da->id_debitoautomatico = $modelArchivo->id;
                        $servicio_da->id_servicio = $row['idservicio'];
                        $servicio_da->tiposervicio = $row['tiposervicio'];
                        $servicio_da->linea = 'FAMILIA '.$nrofamilia .' - MATRICULA ' .$nroServicioFamilia; 
                       
                        if($row['tiposervicio']=='SERVICIOS'){
                            $miServicio = \app\models\ServicioAlumno::findOne($row['idservicio']);
                            $miServicio->estado = 'DA';
                            $procesa = $servicio_da->save() && $miServicio->save();    
                        }else
                        if($row['tiposervicio']=='CUOTAS CONVENIO PAGO'){
                            $miServicio = \app\models\CuotaConvenioPago::findOne($row['idservicio']);
                            $miServicio->estado = 'DA';
                            $procesa = $servicio_da->save() && $miServicio->save();    
                        }                                               
                    }
                }

                $modelArchivo->saldo_enviado = $saldo_total;
                
                $pie="T";
                $pie.=str_pad($cantidad,7,"0",STR_PAD_LEFT); 
                $saldo_total=number_format($saldo_total, 2);
                $saldo_total = str_replace(",","",str_replace(".","",$saldo_total));
                $pie.=str_pad($saldo_total,15,"0",STR_PAD_LEFT);
                $pie.=str_pad("",177," ",STR_PAD_LEFT);
                $pie.="\r\n";
                $contenido.=$pie;

                $modelArchivo->registros_enviados = $cantidad;                
                $modelArchivo->saldo_entrante = 0;                
                $procesa = $procesa && $modelArchivo->save();                

                if($procesa){                    
                    if (!$handle = fopen("$filename", "w")) { 
                        $se_genero = false;  
                        return false;
                        exit;
                    }else {
                        ftruncate($handle,filesize("$filename"));
                    }

                    $link = "";
                    if (fwrite($handle, $contenido) === FALSE){
                        $se_genero = false;
                        return false;
                        exit;
                    }else{ 
                        fclose($handle);	 
                        $se_genero = true; 
                        $archivo  = $modelArchivo->id;
                    }                    
                    
                    return true;           
                } //fin del procesa == TRUE
            }else     
            {
                return false;
            }
        }catch(CDbException $e)
        {
            return false;
        }           
    } //actionGenArc_Patagonia_TC         
    
    private function devolverLinea_PATAGONIA_CBU($nrofamilia, $cbu, $cantidad, $monto, $fecha_vencimiento_pago, $nroServicioFamilia){
        $contenido='';
        $contenido.="D";
        $contenido.='00000000000';
        $contenido.=str_pad($cbu,22," ",STR_PAD_RIGHT);
        
        $identificadorFamilia   = "FG1".str_pad($nrofamilia,5,"0",STR_PAD_LEFT);        
        $contenido.= str_pad($identificadorFamilia,22," ",STR_PAD_RIGHT);
        
        $fechavencimiento = $fecha_vencimiento_pago;
        $fechavencimiento = str_replace("-","",$fechavencimiento);            
        $contenido.=str_replace("-","",$fechavencimiento); 
        
        $contenido.="CUOTA     ";
        $contenido.=str_pad("",15," ",STR_PAD_RIGHT);
        
        $servicio='MATRICULA'.$nroServicioFamilia;
        
        $contenido.=str_pad($servicio,15,"M",STR_PAD_LEFT);
        
        $montoCuota = number_format($monto, 2);
        $montoCuota = str_replace(",","",str_replace(".","",$montoCuota));            
        $contenido.=str_pad($montoCuota,10,"0",STR_PAD_LEFT); 
        $contenido.='P';
        $contenido.='                                                                          30630291727';
        $contenido.="\r\n";
        return $contenido;
    }  
    
    
    /*******************************************************/
    /*******************************************************/    
   /* private function ProcesarPatagoniaTc($id) {
        try {
            $model= $this->findModel($id);
            $filename = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/tc/devoluciones/debitos-" . $model->id.".txt";
            $ok = true;

            if (!file_exists($filename)) {
                return -2;
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                $file = fopen($filename, "r");
                
                $totalIngreso = 0;
                $itemsCorrectos = 0;
                $itemsErrores = 0;

                $tikets = array();
                $nrolinea = -1;
                
                   
                    $linea = fgets($file);
                    $nrolinea += 1;
                    if ($nrolinea == 0)
                        continue;

                    $nro_tarjeta = substr($linea, 26, 16);
                    $resultado_proceso = substr($linea, 129, 3);
                  
                    $fecha_pago = "20" . substr($linea, 54, 2)."-". substr($linea, 52, 2)."-".substr($linea, 50, 2);
                    
                    $monto_debitado = (float) (substr($linea, 69, 6) . "." . substr($linea, 75, 2));
                    $monto_debitado = number_format($monto_debitado, 2, ".", ",");
                    $monto_debitado = str_replace(",", "", $monto_debitado);
                    
                    $procfactura = \app\models\Factura::GeneraFactura($ptoVta, 'DNI', $documento , $modelTiket->importe, $modelTiket->id);
                   
                }
                }else {
                    $transaction->rollback();
                    return -1;
                }
            }
        } catch (Exception $e) {
            return -1;
        }
    }*/
    
    
    
    /*******************************************************/
    /*******************************************************/    
    private function ProcesarPatagoniaCBU($id) {
        try {
            $model= $this->findModel($id);

            $filename = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/cbu/devoluciones/debitos" . $model->id.".txt";

            $ok = true;

            if (!file_exists($filename)) {
                return ['error'=>'1',
                        'resultado'=>'NO EXISTE EL ARCHIVO PARA SU PROCESAMIENTO'];
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                $file = fopen($filename, "r");
                
                $totalIngreso = 0;
                $itemsCorrectos = 0; 
                
                $nrolinea = -1;
                
                while(!feof($file)){  
                    $linea = fgets($file);
                    $nrolinea += 1;
                    if ($nrolinea == 0)
                        continue;

                    $nro_cbu = substr($linea, 12, 22);
                    $resultado_proceso = substr($linea, 151, 3);
                    $nro_familia = (int) substr($linea, 39, 3);
                    $serviciomatricula= trim(substr($linea, 89, 15));
                    echo "FAMILIA: ". $nro_familia ." CBU: " . $nro_cbu . "  RESULTADO: " . $resultado_proceso." ".$serviciomatricula;  
                    echo "<br />";
                    $linea = 'FAMILIA '.$nro_familia.' - '.$serviciomatricula;
                    
                    $servicioDA = \app\models\ServicioDebitoAutomatico::find()->where('id_debitoautomatico='.$id.' and linea='.$linea)->one();
                    $servicioDA->resultado_procesamiento = $resultado_proceso;
                    switch($resultado_proceso){
                        case 'R00':
                            $modelSA = \app\models\ServicioAlumno::findOne($servicioDA->id_servicio);
                            $modelSA->liquidado = '1';
                            $modelSA->estado = 'PA/DA';
                            $modelSA->importe_abonado += $importeDebito;
                            $valid = $valid && $modelSA->save() && $servicioDA->save();
                            $totalIngreso += $importeDebito;
                            $itemsCorrectos += 1;                
                            break;
                        default:
                            $modelSA = \app\models\ServicioAlumno::findOne($servicioDA->id_servicio);
                            $modelSA->liquidado = '0';
                            $modelSA->estado = 'A';
                            $valid = $valid && $modelSA->save() && $servicioDA->save();
                    }
                }
                
                $model->procesado='1';
                $model->registros_correctos=$itemsCorrectos;
                $model->saldo_entrante=$totalIngreso;
                
                if($valid && $model->save()){
                    return ['error'=>'0',
                        'resultado'=>'EL ARCHIVO SE PROCESO CON EXITO'];                    
                }else
                    return ['error'=>'1',
                        'resultado'=>'NO SE PUDO PROCESAR EL ARCHIVO'];
            }
        } catch (Exception $e) {
                    return ['error'=>'1',
                        'resultado'=>'EXCEPCION'];
        }
    }  

    private function actionProcesarDevolucion($id) {
        try {
            $model = $this->findModel($id);
            
            if($model->tipo_archivo=='TC')
                $result = $this->ProcesarPatagoniaTc($model->id); 
            
            if($model->tipo_archivo=='CBU')
                $result = $this->ProcesarPatagoniaCBU($model->id); 
            
            if ($result == -2)
                Yii::app()->user->setFlash('error', 'ERROR NO SE ENCONTRO EL ARCHIVO DE ENTRADA!!!.');
            if ($result == 1)
                Yii::app()->user->setFlash('ok', 'EXITO!!!. SE PROCESO CORRECTAMENTE EL ARCHIVO!!!.');

            $this->redirect(array('view', 'id' => $id));
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'ATENCION!!! <br /> Se Produjo un error severo');
            $this->redirect(array('site/index'));
        }
    }
    
    public function actionProcesar($id){
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $model->archivoentrante = UploadedFile::getInstance($model, 'archivoentrante');
            $ruta = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/cbu/devoluciones/debitos".$model->id.".txt";
            
            if($model->archivoentrante->saveAs($ruta)){
                echo "SIII";
                $this->actionProcesarDevolucion($id);    
            }                
            else
                echo "NOOO";
        }

        return $this->renderAjax('procesar',[
			'model'=>$model,                        
		]);
    }
    
    /********************************************************************/
    /********************************************************************/
    public function actionDescargarArchivoEnvio($id){    
        $modelDebito = $this->findModel($id);
        
        if($modelDebito->tipo_archivo=='CBU')
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/cbu/generados";
        else
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/tc/generados";
            
        $filename = 'debitos-'. $modelDebito->id .'.txt';     
        $filename = $carp_cont."/".$filename;    
                
        if(!file_exists($filename)){
            Yii::app()->user->setFlash('error', 'ATENCION!!! <br /> No existe el archivo para su procesamiento/descarga');
            return $this->redirect(['debito-automatico/administrar']);
        }else{
            $url_pdf = \yii\helpers\Url::to(['debito-automatico/descarga-txt', 'id' => $id]);
            Yii::$app->response->format = 'json';
            return ['result_error' => '0', 'result_texto' => $url_pdf];
        }
    } 
    
    public function actionDescargaTxt($id) { 
        $modelDebito = $this->findModel($id);
        
        if($modelDebito->tipo_archivo=='CBU'){
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/cbu/generados";
            $file = 'DEBITOS.txt';
        }            
        else{
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados/patagonia/tc/generados";   
            $file = 'DEBLICQ.txt';
        }
            
        
        $filename = 'debitos-'. $modelDebito->id .'.txt';     
        $ruta_archivo = $carp_cont."/".$filename; 
        
        
        if (is_file($ruta_archivo)) {
           $size = filesize($ruta_archivo);   
               
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Content-Description: File Transfer');
                header('Content-Type: text/text');
                header('Content-Disposition: attachment; filename="'.$file.'"');
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: max-age=0');      
                ob_clean();
                flush();
                readfile($ruta_archivo);
                exit;
        }
    } 
    
    /****************************************************************/
    /*****************************************************************/
    public function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color) ));
    }
    
    public function actionConvertirAExcel($id){
        ini_set('memory_limit', -1);
        set_time_limit(240);
        try{
            $modelDA = $this->findModel($id);
        
            $serviciosDA = \app\models\ServicioDebitoAutomatico::find()->where('id_debitoautomatico='.$id)->all();
        
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); 
            $this->cellColor($objPHPExcel, 'A1', 'F28A8C');
            $this->cellColor($objPHPExcel, 'B1', 'F28A8C');
            $this->cellColor($objPHPExcel, 'C1', 'F28A8C');
            $this->cellColor($objPHPExcel, 'D1', 'F28A8C');
            $this->cellColor($objPHPExcel, 'E1', 'F28A8C');                
                
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'FAMILIA');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'FOLIO');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'ALUMNO');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'SERVICIO');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'MONTO SERVICIO ENVIADO'); 
            
            $letracolumnainicio = 'A';
            $letrafilainicio = 3;

            $i = 0;                        
            $contador = count($serviciosDA);
            
            while ($i < $contador) {
                $letrafilainicio1 = (string) $letrafilainicio;
                $columnaA = 'A' . $letrafilainicio1;
                $columnaB = 'B' . $letrafilainicio1;
                $columnaC = 'C' . $letrafilainicio1;
                $columnaD = 'D' . $letrafilainicio1;
                $columnaE = 'E' . $letrafilainicio1;

                if($serviciosDA[$i]['tiposervicio']=='SERVICIOS'){
                    $servicioAlumno = \app\models\ServicioAlumno::findOne($serviciosDA[$i]['id_servicio']);
                    $alumno = \app\models\Alumno::findOne($servicioAlumno->id_alumno);
                    $familia = \app\models\GrupoFamiliar::findOne($alumno->id_grupofamiliar);

                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $familia->apellidos);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $familia->folio);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, $alumno->miPersona->apellido . " " .$alumno->miPersona->nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, $servicioAlumno->datosMiServicio);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaE, $servicioAlumno->importeAbonar);
                }else
                if($serviciosDA[$i]['tiposervicio']=='CUOTAS CONVENIO PAGO'){
                    $cuotaConvenioPago = \app\models\CuotaConvenioPago::findOne($serviciosDA[$i]['id_servicio']);
                    $convenioPago = \app\models\ConvenioPago::findOne($cuotaConvenioPago->id_conveniopago); 
                    $familia = \app\models\GrupoFamiliar::findOne($convenioPago->id_familia);

                    $objPHPExcel->getActiveSheet()->setCellValue($columnaA, $familia->apellidos);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaB, $familia->folio);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaC, "FAMILIA");
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaD, "CUOTA CONVENIO PAGO - NRO CONVENIO: " . $convenioPago->id);
                    $objPHPExcel->getActiveSheet()->setCellValue($columnaE, $servicioAlumno->importeAbonar);
                }
                $i = $i + 1;
                $letrafilainicio += 1;
            } 
                
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
            $nombre_archivo = "debitoAutomaticoExcel" . $modelDA->id . ".xlsx";                                
            $ruta_archivo = $carp_cont . "/" . $nombre_archivo;

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            $objWriter->save($ruta_archivo);
            $url_pdf = \yii\helpers\Url::to(['debito-automatico/descarga-excel', 'id' => $id]); 
            
            
            
            Yii::$app->response->format = 'json';
            return ['result_error' => '0', 'result_texto' => $url_pdf];
        }catch (Exception $e) {
            Yii::$app->response->format = 'json';
            return ['result_error' => '1', 'result_texto' => 'ERROR'];
        }
    }
    
    public function actionDescargaExcel($id) {  
        $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos                                       
        $ruta_archivo = $carp_cont . "/debitoAutomaticoExcel" . $id.".xlsx";
        
        if (is_file($ruta_archivo)) {
            $size = filesize($ruta_archivo);
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=debitos.xlsx");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);
            readfile($ruta_archivo);
        }
    } 
            

}
