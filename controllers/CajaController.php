<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\db\Query;

use app\models\Alumno;
use app\models\search\AlumnoSearch;
use app\models\GrupoFamiliar;
use app\models\search\GrupoFamiliarSearch;
use app\models\Tiket;

/**
 * Description of CajaController
 *
 * @author agus
 */
class CajaController extends Controller {
    
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
                        'actions' => ['cobrar','cobro-servicios','cobro-ingreso','pdf-tiket','detalle-tiket','down-pdf'],
                        'allow' => true,
                        'roles' => ['cobrarServicios'],
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
    
    
    public function actionCobrar($oper = null) {
        if ($oper == null || $oper >2) 
            throw new \yii\web\HttpException(404,'no se selecciono una operacion de cobro apropiada.');        
        try {
                Yii::$app->session->remove('srvpagar');
                Yii::$app->session->set('srvpagar',array());
                
                $searchGrupoFamiliar = new GrupoFamiliarSearch();
                $dataFamilias = $searchGrupoFamiliar->search(Yii::$app->request->post());
            }
            catch (Exception $e) {
                Yii::$app->session->setFlash('error', 'ATENCION!!! <br /> Se Produjo un error severo');
                $this->redirect(['site/index']);
            }
            return $this->render('cobrar', [
                'dataFamilias' => $dataFamilias,
                'searchGrupoFamiliar' => $searchGrupoFamiliar,                            
                'oper' => (int) $oper
            ]);
    }
    
    
    /*****************************************************/
    /******************************************************/
    public function actionDetalleTiket($tiket) {
        try {
            $modelTiket = Tiket::findOne($tiket);            
            $familia = GrupoFamiliar::findOne($modelTiket->id_cliente);
            
            $serviciosTiket = \app\models\ServicioTiket::find()->where('id_tiket=' . $modelTiket->id)->all();            
            
            return  $this->render('detalleTiket', [
                        'tiket' => $modelTiket,                            
                        'familia' => $familia,
                        'serviciosTiket' => $serviciosTiket
                    ]);        
            
        } catch (Exception $e) {            
            Yii::app()->user->setFlash('error', 'ATENCION!!! <br /> Se Produjo un error severo');
            $this->redirect(array('site/index'));
        }
    }

    /*******************************************************************/
    public function actionCobroIngreso($cliente) {
        try {
            $modelFamilia = GrupoFamiliar::findOne($cliente);
           
            if (empty($modelFamilia)) {
                Yii::$app->session->setFlash('error', 'ATENCION!!! <br /> Debe seleccionar a un cliente.');
                $this->redirect(['caja/cobrar','oper'=>2]);
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                
                $modelTiket = new Tiket;
                $modelTiket->id_cliente =$modelFamilia->id;
                $modelTiket->montoabonado = 0;
                $modelTiket->importeservicios = 0;
                $modelTiket->pagototal = 0;
                
                
                //modelo destinado a registrar un ingreso en caja,
                /*
                 * Para poder registarar pagos libres, que no pertenezcan a un determinado
                 * categoeria de servicios, generamos un tiket libre, para que en el detalle
                 * ueden ingrer lo que deseamos;luego se crea un servicio con categeoria
                 * generica; ya que el tiket puede estar no abonado; y serlo en un futuro
                 */

                if ($modelTiket->load(Yii::$app->request->post()) && $modelTiket->save()) {
                    $valid = true;
                         
                    $resultMovimiento = \app\models\Cuentas::AcentarMovimientosCaja($modelTiket->cuentapagadora, 'INGRESO', $modelTiket->importe, $modelTiket->detalles, $modelTiket->fecha_tiket, 'COBRO INGRESOS LIBRES', $modelTiket->id_formapago, $modelTiket->id);
                    $valid = $valid && $resultMovimiento;
                    if($valid){
                        $transaction->commit();                        
                        Yii::$app->session->setFlash('ok', 'SE COBRO CON EXITO!!!');
                        return $this->redirect(['detalle-tiket', 'tiket' => $modelTiket->id]);                          
                    }   
                }
            }//fin del model->validate()            
                        
            return $this->render('formularioCobroIngreso', [
                        'modelFamilia' => $modelFamilia,
                        'modelTiket' => $modelTiket
            ]);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'ERROR!!!');
            $this->redirect(['site/index']);
        }
    }

    
    /*******************************************************************/
    /*******************************************************************/
    private function deudaFamilia($familia){
        $query = "SELECT sa.id as nroservicio, id_alumno as idalumno, importe_servicio as montoservicio, 
                                 importe_descuento,importe_abonado, 'SERVICIOS' as tiposervicio 
                            FROM servicio_alumno sa 
                             INNER JOIN alumno a ON (a.id = sa.id_alumno)
                             WHERE sa.estado='A' and  a.id_grupofamiliar=$familia
                        UNION
                            SELECT ccp.id as nroservico, id_familia as idalumno, monto as montoservicio, '0' as importe_descuento, ccp.importe_abonado, 'CUOTAS' as tiposervicio
                            FROM `cuota_convenio_pago` ccp
                              INNER JOIN convenio_pago cp ON (ccp.id_conveniopago= cp.id)
                            WHERE ccp.estado='A'  and cp.id_familia=$familia";

        $queryCount = "SELECT COUNT(*) FROM ($query) as total";
        $queryCount= \Yii::$app->db->createCommand($queryCount)->queryScalar();

        $serviciosImpagos = new \yii\data\SqlDataProvider([
            'sql' => $query,   
            'key'=>'nroservicio',
            'totalCount' => $queryCount,
            'pagination' => [
                'pageSize' => 3,
            ],
            'sort' => [
                'attributes' => ['nroservicio', 'idalumno', 'montoservicio','importe_descuento','importe_abonado','tiposervicio'],
            ],                    
        ]);   
        
        return $serviciosImpagos;
    }
    
    public function actionCobroServicios($cliente){
        $modelFamilia = GrupoFamiliar::findOne($cliente);
        if (empty($modelFamilia))
            throw new CHttpException(404, 'The requested page does not exist.');

        try {    
            $transaction = Yii::$app->db->beginTransaction();

            $modelTiket = new Tiket();
            $modelTiket->importe = 0;
            $modelTiket->importeservicios = 0;
            $totalTiket = 0;
            
            $seleccion = Yii::$app->session->get('srvpagar');
            if(($serviciosSeleccionados = Yii::$app->request->post('selection')) !== null){                                
                foreach ($serviciosSeleccionados as $arow){  
                    $seleccion[$arow] = $arow;                  
                }         
                Yii::$app->session->set('srvpagar',$seleccion);                
            } 
            
            if ($modelTiket->load(Yii::$app->request->post())) {
                $modelTiket->importe = $modelTiket->montoabonado; 
                $modelTiket->detalles = 'COBRO SERVICIOS';                
                $modelTiket->id_cliente = $modelFamilia->id;
                $modelTiket->cantidadservicios = count($serviciosSeleccionados);
                            
                if ($modelTiket->save()) {
                    $fechaTiket = $modelTiket->fecha_tiket;
                    $valid = true;

                    foreach ($serviciosSeleccionados as $key => $value) {
                        $des = explode('-', $value);

                        $modelServicioTiket = new \app\models\ServicioTiket();
                        $modelServicioTiket->id_tiket = $modelTiket->id;                            
                        $modelServicioTiket->id_servicio = $des[1];
                            
                        if($des[0]=='SER'){
                            $modelServicioTiket->tipo_servicio = Tiket::SERVICIO_CUOTA;
                            
                            $modelServicioAlumno = \app\models\ServicioAlumno::findOne($des[1]);
                            if($modelTiket->pagototal=='1'){
                                $importeRestante = $modelServicioAlumno->importeRestante;
                                $totalTiket += $importeRestante;
                                $modelServicioAlumno->importe_abonado += $importeRestante;
                                $modelServicioTiket->importe = $importeRestante;
                            }else{
                                $totalTiket += $modelTiket->montoabonado;
                                $modelServicioAlumno->importe_abonado += $modelTiket->montoabonado;
                                $modelServicioTiket->importe = $modelTiket->montoabonado;
                            }
                                
                            if($modelServicioAlumno->importe_abonado == $modelServicioAlumno->importe_servicio){                                
                                $modelServicioAlumno->estado = 'PA';
                            }
                                
                            $modelServicioAlumno->fecha_cancelamiento = $fechaTiket; 
                            $modelServicioTiket->tipo_servicio=\app\controllers\ConfController::SERVICIO_SERVICIOS;
                            $valid = $valid &&  $modelServicioAlumno->save();
                        }else
                        if($des[0]=='CCP'){
                            $modelServicioTiket->tipo_servicio = Tiket::SERVICIO_CUOTA;
                            
                            $modelCuotaCP = \app\models\CuotaConvenioPago::findOne($des[1]);                                
                            if($modelTiket->pagototal=='1'){
                                $importeRestante = $modelCuotaCP->importeRestante;
                                $totalTiket += $importeRestante;
                                //$totalTiket += $totalTiket;
                                $modelCuotaCP->importe_abonado+= $importeRestante;
                                $modelServicioTiket->importe = $importeRestante;  
                            }else{
                                $totalTiket += $modelTiket->montoabonado;
                                $modelCuotaCP->importe_abonado += $modelTiket->montoabonado;
                                $modelServicioTiket->importe = $modelTiket->montoabonado;
                            }
                            if($modelCuotaCP->importe_abonado == $modelCuotaCP->monto){                                
                                $modelCuotaCP->estado = 'PA';
                            }  
                            $modelServicioTiket->tipo_servicio=\app\controllers\ConfController::SERVICIO_CONVENIO_PAGO;
                            $valid = $valid &&  $modelCuotaCP->save();
                        }    
                            

                        $valid = $valid && $modelServicioTiket->save();
                    }                     
                        
                    $resultMovimiento = \app\models\Cuentas::AcentarMovimientosCaja($modelTiket->cuentapagadora, 'INGRESO', $modelTiket->importe, $modelTiket->detalles, $fechaTiket, 'Nro Tiket: ' . $modelTiket->id, $modelTiket->id_formapago, $modelTiket->id);

                    if ($valid) {                      
                        $transaction->commit();                        
                        Yii::$app->session->setFlash('ok', 'SE COBRO CON EXITO!!!');
                        return $this->redirect(['detalle-tiket', 'tiket' => $modelTiket->id]);
                    }
                }
            }
            
            
                
                
            $serviciosImpagos = $this->deudaFamilia($modelFamilia->id);
            
            return $this->render('formularioCobroServicio', [   
                    'modelTiket'=>$modelTiket,
                    'serviciosImpagos' => $serviciosImpagos,
            ]);
            
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'ERROR en la operación!!!');
            $this->redirect(['site/index']);
        }
    }
    
    
    
    /******************************************************************/
    /******************************************************************/
        private function armarPdfFactura($idTiket, $idCliente) {
       
        $modelTiket = Tiket::findOne($idTiket);
        $cliente = GrupoFamiliar::findOne($modelTiket->id_cliente);

        $encabezado = "<table border='1' cellpadding='0' cellspacing='0'  style='width:100%;'>";
        $encabezado .= "<thead>
                            <tr>
                            <td style='width:50%; text-align:center; font-size: 10px; padding-top: 8px; padding-bottom: 8px;'>
                                <img src='./images/logodonbsco2.png' alt='logo' class='img-responsive'/><br />
                            </td>
                            <td style='width:50%; text-align:center;'>
                                <br />
                                <b> Recibo C </b> <br />
                                <b> Nro ". \str_pad($modelTiket->id, 8, "0", \STR_PAD_LEFT) . "</b> <br />
                                <b> Fecha: " . \Yii::$app->formatter->asDate('2017-01-01') . "</b>
                                    
                            </td>
                        </tr>
                        <tr> 
                        <td style='padding:8px;' colspan='2'>
                            <b> Familia: </b> " . $cliente->apellidos ." (Folio: ".$cliente->folio. " )<br />
                            <b> Responsable: </b> " .    $cliente->miResponsableCabecera . "<br />
                            <b> Dirección: </b>
                        </td>                    
                       </tr>
                       </thead>";

        $cuerpo = "<tbody><tr> 
                     <td  tyle='padding-top: 10px; padding-bottom:10px;' colspan='2'>";
        $cuerpo .= "<table border='0' cellpadding='0' cellspacing='0'  style='width:100%;'>";
        $cuerpo .= "<tr><td style='padding-left: 8px; padding-right:8px;'> <b> En concepto de: </b><br /><br /></td></tr>";

        //buscamos los servicios de la factura
        $serviciosTiket = \app\models\ServicioTiket::find()->where('id_tiket=' . $modelTiket->id)->all();
        if (count($serviciosTiket) == 0) {
            $cuerpo .= "<tr><td style='padding-left: 8px; padding-right:8px;'> " . $modelTiket->detalles . "</td></tr>";
        } else {
            foreach ($serviciosTiket as $servicio) {
                
                    $cuerpo .= "<tr><td style='padding-left: 8px; padding-right:8px;'>" . $servicio->miDetalle . "</td></tr>";                    
                
            }
        }
        $cuerpo .= "</table>";
        $cuerpo .= '</td></tr></tbody>';

        $pie = "<tr><td colspan='2' style='text-align:right; padding-top:10px;padding-right:10px;'>TOTAL:  ";
        $pie .= " $ $modelTiket->importe</td></tr>";
        
        $pie .= '</tfoot></table>';

        $html = $encabezado . $cuerpo . $pie;
        return $html;
    }        

    
    
    public function actionPdfTiket($nroTiket) {
        $modelTiket = Tiket::findOne($nroTiket);
        if (!($modelTiket)) {
            
        } else {
            
            $cliente = GrupoFamiliar::findOne($modelTiket->id_cliente);
            $plantilla = $this->armarPdfFactura($modelTiket->id, $cliente->id);

            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
            $nombre_archivo = "tiket-" . $modelTiket->id . ".pdf";
            $archivo = $carp_cont . '/' . $nombre_archivo;

            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => 'Krajee Report Title'],
                'methods' => [
                    'SetHeader' => ['Krajee Report Header'],
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);
            $pdf->output($plantilla, $archivo, 'F');
            $url_pdf = \yii\helpers\Url::to(['caja/down-pdf', 'name' => $nombre_archivo]);
            Yii::$app->response->format = 'json';
            return ['result_error' => '0', 'result_texto' => $url_pdf];
        }
    }

    public function actionDownPdf($name) {
        $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
        $archivo = $carp_cont . '/' . $name;
        if (is_file($archivo)) {
            $size = filesize($archivo);
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=$name");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);
            readfile($archivo);
            unlink($archivo);
        }
    }

// FIN DescargaPdfConvenio     
    
}
