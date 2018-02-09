<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use kartik\mpdf\Pdf;


use app\models\ConvenioPago;
use app\models\search\ConvenioPagoSearch;
use app\models\Abogado;
use app\models\Persona;
use app\models\CuotaConvenioPago;
use app\models\ServicioConvenioPago;

/**
 * ConvenioPagoController implements the CRUD actions for ConvenioPago model.
 */
class ConvenioPagoController extends Controller
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
                        'allow' => true,
                        'roles' => ['gestionarConvenioPago'],
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
     * Lists all ConvenioPago models.
     * @return mixed
     */
    public function actionAdministrar()
    {
        try{
            $searchModel = new ConvenioPagoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }catch(\Exception $e){
            Yii::error('Administrar Convenio Pago '.$e);
            Yii::$app->session->setFlash('error','ERROR SEVERO!!!');
        } 
        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
        
    }    
    
    /********************************************************************/
    /********************************************************************/
    /**
     * Displays a single ConvenioPago model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $queryServicios = ServicioConvenioPago::find();
        $queryServicios->andFilterWhere([           
            'id_conveniopago' => $id,            
        ]);   
        $misServicios = new ActiveDataProvider([
            'query' => $queryServicios,
        ]);
        
        $queryCuotas = CuotaConvenioPago::find();
        $queryCuotas->andFilterWhere([           
            'id_conveniopago' => $id,            
        ]);   
        $misCuotas = new ActiveDataProvider([
            'query' => $queryCuotas,
        ]);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'misServicios'=>$misServicios,
            'misCuotas'=>$misCuotas,
            
        ]);
    }
    
    
    /**
     * Finds the ConvenioPago model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConvenioPago the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConvenioPago::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDelete($id){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = $this->findModel($id);
            $cuotasPagas = CuotaConvenioPago::find()->where("(pagada='1' or importe_abonado >0) and id_conveniopago=".$id)->all();
            if(!empty($cuotasPagas)){
                Yii::$app->session->setFlash('error','No se puede eliminar un CP con cuotas ya abonadas!!!');
                $this->redirect(['administrar']);  
            }else{
                $valid = true;
                
                //eliminamos los Servicios y los reconstruimos paa que lopuedan facturar
                $misServicios = ServicioConvenioPago::find()->where('id_conveniopago='.$id)->all();
                if(!empty($misServicios)){
                    foreach($misServicios as $servicio){
                        $modelServicioAlumno = \app\models\ServicioAlumno::findOne($servicio->id_servicio); 
                        $modelServicioAlumno->liquidado='0';
                        $modelServicioAlumno->estado='A';
                        $valid =$valid && $modelServicioAlumno->save();
                    }
                    $valid =$valid && ServicioConvenioPago::deleteAll('id_conveniopago='.$id); 
                }
                if($valid && CuotaConvenioPago::deleteAll('id_conveniopago='.$id)
                        && ($model->delete())){
                    $transaction->commit();
                    Yii::$app->session->setFlash('ok','Se elimino correctamente!!!');
                    $this->redirect(['administrar']);
                }else{
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error','No se puedo eliminar el CP!!!');
                    $this->redirect(['administrar']);
                }
            }
        }
        catch(Exception $e){
            Yii::$app->session->setFlash('error','ERROR SEVERO!!!');
            $this->redirect(['administrar']);
        } 
    }
    
    /***********************************************************/
    /***********************************************************/ 
    /*
     * Alta ConvenioPago
     * 
     * Accion que se encarga de dar el alta de un convenio de pago, 
     * para un determindo cliente. Se apoya de un accion privada
     * que maneja el alta propiamente dicha
     * 
     * Como primer llamada renderiza un formulario, para buscar al cliente,
     * una vez seleccionado el cliente, llamamos a la funcion GenerarPLanPago
     * la que se encargara delproceso de alta.
     * 
     */
    public function actionAlta(){
        try{            
            //limpiamos las variables de session que podian quedar guardadas
            //estas variables son las que mantineen el estado de las cuotas seteadas para crear el convenio de pago
            Yii::$app->session->remove('srvpagar');
            Yii::$app->session->set('srvpagar',array());

            $modelGrupoFamiliar = new \app\models\GrupoFamiliar(); 
             
            $query = \app\models\GrupoFamiliar::find(); 
            $query->joinWith(['responsables r','responsables.persona p']);                    
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            
            if($modelGrupoFamiliar->load(Yii::$app->request->post())){
                $query->andFilterWhere(['like', 'apellidos', $modelGrupoFamiliar->apellidos])
                      ->andFilterWhere(['like', 'folio', $modelGrupoFamiliar->folio]);
                $query->andFilterWhere(['like', 'p.apellido', $modelGrupoFamiliar->responsable]);
            }
        }
        catch (\Exception $e){ 
            Yii::error('Alta Convenio Pago '.$e);
            Yii::$app->session->setFlash('error','ATENCION!!! <br /> Se Produjo un error severo');
            
        }
        return $this->render('alta',[
            'modelGrupoFamiliar'=>$modelGrupoFamiliar, 
            'dataClientes'=>$dataProvider,   
        ]);
    } //fin actionALTA    
    
    
    /*
     * seleccion de servicios a integrar en el convenio de pago
     */
    public function actionAltaServicios(){
        
        $familia = Yii::$app->request->get('familia');

        $modelFamilia = \app\models\GrupoFamiliar::findOne($familia);  
        if(!$modelFamilia)
            throw new NotFoundHttpException('Grupo Familiar inexistente.');
            
        try{    
            $serviciosImpagos = \app\models\ServicioAlumno::DevolverServiciosImpagosLibres($modelFamilia->id);
            
            /*
             *  Varibales de session para mantener las seleccion en el GRidView.
             */              
            $seleccion = Yii::$app->session->get('srvpagar');
            
            $noselects = Yii::$app->request->post('noselects');
            $selects = Yii::$app->request->post('selects');
            $selection = Yii::$app->request->post('selection');
            
            if(!empty($noselects)) {
               $noselect = explode(",", $noselects);
               foreach($noselect as $sel){
                   unset($seleccion[$sel]);
               }
            }
            
            if(!empty($selects)){
               $select = explode(",", $selects);
               foreach($select as $sel){
                   $seleccion[$sel] = $sel; 
               }    
            }            
            
            if(isset($_POST['selection'])){                                
                foreach ($_POST['selection'] as $arow){  
                    $seleccion[$arow] = $arow;                  
                }         
            }
            
            Yii::$app->session->set('srvpagar',$seleccion);
            
            $envios = Yii::$app->request->post('envios');
            if(isset($_POST['envios'])){
                return $this->redirect(['generar-plan-pago','familia'=>$familia]);
            }
        }catch(\Exception $e){
            Yii::error('AltaServicios Convenio Pago '.$e);
            Yii::$app->session->setFlash('error','ATENCION!!! <br /> Se Produjo un error severo');
        }   
        return $this->render('alta-servicios',[
            'modelFamilia'=>$modelFamilia,
            'serviciosImpagos'=>$serviciosImpagos 
        ]); 
    }
    
    public function actionGenerarPlanPago($familia){ 
        $transaction = Yii::$app->db->beginTransaction();
        
        try{            
            $modelFamilia = \app\models\GrupoFamiliar::findOne($familia);
            
            $modelConvenionPago = new ConvenioPago;
            $modelConvenionPago->id_familia = $modelFamilia->id;
            
            if(isset($_POST['CuotaConvenioPago']) && count($_POST['CuotaConvenioPago']) > 0){ 
                $modelCuotasConvenioPago = array();
                foreach($_POST['CuotaConvenioPago'] as $key => $one){               
                    $modelCuotasConvenioPago["$key"] = new CuotaConvenioPago();
                }
                Model::loadMultiple($modelCuotasConvenioPago, Yii::$app->request->post());
                Model::validateMultiple($modelCuotasConvenioPago);               
            }else{
                $modelCuotasConvenioPago = array(); //array de modelos Investigados para la carga masiva
                $modelCuotasConvenioPago[0] = new CuotaConvenioPago();   
            }
            
            $total=0;
            
            $servicios = Yii::$app->session->get('srvpagar');
            
            if(empty($servicios)){
                $modelConvenionPago->con_servicios='0';
                $dataProvider = null;
            }else{
                $modelConvenionPago->con_servicios='1';
                $query = \app\models\search\ServicioAlumnoSearch::find();
                $query->alias('t');
                $query->joinWith(['servicio so']);
                $query->where(['IN', 't.id', $servicios]);
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                ]);

                foreach($servicios as $idservicio){
                    $modelServicioAbogado = \app\models\ServicioAlumno::findOne($idservicio);
                    $saldo = (float)$modelServicioAbogado->importeAbonar;
                    $total+=$saldo;
                }                    
            }
                    
            $modelConvenionPago->saldo_pagar = $total;           
                
            if($modelConvenionPago->load(Yii::$app->request->post())){ 
                if($modelConvenionPago->save()){                    
                    $totalcuotas = 0;
                    $valid = true;
                    $nrocuota = 1;
                    
                    foreach($modelCuotasConvenioPago as $key => $cuota){
                        $cuota->id_conveniopago = $modelConvenionPago->id;
                        $cuota->nro_cuota = $nrocuota;
                        $cuota->importe_abonado='0';
                        $cuota->estado='A';
                        $nrocuota+=1;
                        $totalcuotas += $cuota->monto;
                        $valid = $valid && $cuota->save();
                    }
                    if($modelConvenionPago->saldo_pagar != $totalcuotas){
                        $valid = false;
                        $modelConvenionPago->addError('saldo_pagar','El saldo a pagar debe coincidir con el monto total de las cuotas!!!');
                    }
                    
                    if(!empty($servicios)){
                        foreach($servicios as $idservicio){
                            $modelServicioAlumno = \app\models\ServicioAlumno::findOne($idservicio);
                            $modelServicioAlumno->estado='CP';
                            $modelServicioCP = new ServicioConvenioPago();
                            $modelServicioCP->id_conveniopago = $modelConvenionPago->id;
                            $modelServicioCP->id_servicio = $modelServicioAlumno->id;
                            $valid = $valid && $modelServicioCP->save() && $modelServicioAlumno->save();                   
                        }
                    }
                    
                    if($valid){
                        $transaction->commit();
                        Yii::$app->session->setFlash('ok', Yii::$app->params['cargaCorrecta']); 
                        //$this->actionEnviarCorreoCP($modelConvenionPago->id);
                        return $this->redirect(['view', 'id' => $modelConvenionPago->id]);   
                    }                       
                }
            }    
            
            return $this->render('altaConvenio',[ 
                                'modelFamilia'=>$modelFamilia,
                                'modelConvenionPago'=>$modelConvenionPago,
                                'modelCuotasConvenioPago'=>$modelCuotasConvenioPago,
                                'dataProvider'=>$dataProvider,
                            ]);
        }
        catch(Exception $e){
            Yii::$app->session->setFlash('error','ERROR EN LA GENERACION DEL CONVENIO DE PAGO!!!');
            $this->redirect(['alta']);
        } 
    }

    /**************************************************************/
    /****************************************************************/
    /*
     * Agrega un registro modelo de datos Investigado al formulario de una solicitud.
     * Es invocado a traves de una peticion ajax, y renderiza campos input,
     * en el formulario del que es llamado.
     */
    public function actionAddCuota(){
        try{
            $modelCuota = new CuotaConvenioPago();
            $nro_inv =  (int) $_POST['nro'];               

            if (Yii::$app->request->isAjax){  
                Yii::$app->response->format = 'json';
                return ['status' => 'formulario', 'error' => '0',
                        'vista' => $this->renderAjax('_formCuota', ['model' => $modelCuota,'ordn'=>$nro_inv])]; 
            }
        }
        catch(Exception $e){               
            Yii::app()->user->setFlash('error','ERROR!!! No se puedo realizar la eliminacion!!!'.$e);
            $this->redirect(array('administracion'));                 
        }
    }// FIN addJusticiado      
    
    /*************************************************************/
    /*************************************************************/
    private function armarPdfconvenio($idConvenio) {
        ini_set('memory_limit', -1);
        
        $convenio = $this->findModel($idConvenio);
        $clienteConvenio = \app\models\GrupoFamiliar::findOne($convenio->id_familia);

        $queryServicios = ServicioConvenioPago::find();
        $queryServicios->andFilterWhere(['id_conveniopago' => $convenio->id]);
        $misServicios = new ActiveDataProvider(['query' => $queryServicios]);
        $misServicios = $misServicios->getModels();

        $queryCuotas = CuotaConvenioPago::find();
        $queryCuotas->andFilterWhere(['id_conveniopago' => $convenio->id]);
        $misCuotas = new ActiveDataProvider(['query' => $queryCuotas]);
        $misCuotas = $misCuotas->getModels();

        $html = '<table repeat_header="1"  cellpadding="1" cellspacing="1" width="100%" border="1">';
        $html .= '<thead>                  
              <tr>
                    <tr><th align="center"><b> CONVENIO PAGO Nº '. $idConvenio.' <br />' . date('d-m-Y') . '</b></th>
                        <th align="center"><img src="./images/logo.jpg" alt"" /></th>
              </tr>
              <tr>
               <td style="padding:5px;" colspan="2"> GRUPO FAMILIAR: ' . $clienteConvenio->apellidos . ', ' . $clienteConvenio->folio . '<br /><br />
                 <span class="datAbog"> <b> Responsable:  ' . $clienteConvenio->miResponsableCabecera . '</span><br />
               </td>
              </tr>
            </thead>';

        $html .= '<tbody>';

        $i = 0;
        $contador = count($misServicios);

        if ($contador > 0) {
            $i = 0;
            $html .= '<tr><td colspan="2" style="padding-left: 10px;">';
            $html .= '<b>SERVICIOS INTEGRADOS:<b/><br /><br />';
            while ($i < $contador) {
                $r = $misServicios[$i]['id_servicio'];
                $servicioTomado = \app\models\ServicioAlumno::findOne($misServicios[$i]['id_servicio']);
                $html .= "<span style='display:block; padding-top:20mm; padding-left:15px; padding-right:15mm;'>" . $servicioTomado->datosMiServicio." ---  Importe Asignado: $ ". number_format($servicioTomado->importeRestante,2) .'</span><br />';
                $i++;
            }
            $html .= '</td></tr>';
        } else {
            $html .= '<tr><td colspan="2" style="padding-left: 15px;">';
            $html .= '<b>Detalle Pago:<b/><br /><br />';
            $html .= $convenio->descripcion;
            $html .= '</td></tr>';
        }

        $i = 0;
        $contadorCuotas = count($misCuotas);

        if ($contadorCuotas > 0) {
            $html .= '<tr><td colspan="2">';
            $html .= '<table cellpadding="1" cellspacing="1" width="100%">
                <thead> 
                  <tr align="center" style="background:rgb(210,115,115); font-weight:bold;  text-align:center; font-size: 16px;">
                    <td  colspan="5"> Detalle Cuotas </td>
                  </tr>
                  <tr align="center" style="background:rgb(210,115,115); font-weight:bold;  text-align:center; font-size: 16px;">
                    <td  style="background:rgb(210,115,115); text-align:center; font-weight:bold; font-size: 14px;" width="10%"> Nro </td>
                    <td  style="background:rgb(210,115,115); text-align:center; font-weight:bold; font-size: 14px;" width="12%"> Vencimiento Pago</td>
                    <td  style="background:rgb(210,115,115); text-align:center; font-weight:bold; font-size: 14px;" width="48%"> Detalle Abono </td>                   
                    <td  style="background:rgb(210,115,115); text-align:center; font-weight:bold; font-size: 14px;" width="15%"> IMPORTE </td>
                    <td  style="background:rgb(210,115,115); text-align:center; font-weight:bold; font-size: 14px;" width="15%"> IMP.ABONADO </td>
                  </tr>
                </thead>';
            $html .= '<tfoot><tr><td colspan="4" align="left" style="padding-top:8px;padding-bottom:8px;">';
            $html .= 'MONTO TOTAL: ' . $convenio->saldo_pagar;
            $html .= '</td></tr></tfoot>';

            $html .= '<tbody>';
            while ($i < $contadorCuotas) {
                $html .= '';
                $html .= '
                            <tr class="odd">
                              <td class="odd" style="text-align:center;  font-size: 10px;"  width="9%">&nbsp;' . $misCuotas[$i]['nro_cuota'] . '</td>
                              <td class="odd" style="text-align:center;    font-size: 10px;"  width="16%">&nbsp;' . \Yii::$app->formatter->asDate($misCuotas[$i]['fecha_establecida']) . '</td>';
                if ($misCuotas[$i]['estado'] == 'A')
                    $html .= '<td class="odd" style="text-align:center;    font-size: 10px;"  width="16%">ABONADA</td>';
                else
                    $html .= '<td class="odd" style="text-align:center;    font-size: 10px;"  width="16%">PENDIENTE</td>';

                $html.= '<td class="odd" style="text-align:center;  font-size: 10px;"  width="17%">&nbsp;' . $misCuotas[$i]['monto'] . '</td>';
                $html.= '<td class="odd" style="text-align:center;  font-size: 10px;"  width="17%">&nbsp;' . $misCuotas[$i]['importe_abonado'] . '</td>';
                $html.= '</tr>';
                $i++;
            }

            $html .= '</tbody></table></td></tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }   
    
    
    
    public function actionPdf($id){
        try{   
            $plantilla = $this->armarPdfconvenio($id);
            
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
            $nombre_archivo = "convenioPago".Yii::$app->user->id .".pdf";
            $archivo = $carp_cont.'/'.$nombre_archivo;
            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                'format' => Pdf::FORMAT_A4, 
                'orientation' => Pdf::ORIENT_PORTRAIT, 
                'destination' => Pdf::DEST_BROWSER, 
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}', 
                'options' => ['title' => 'Krajee Report Title'],
                'methods' => [ 
                    'SetHeader'=>['Krajee Report Header'], 
                    'SetFooter'=>['{PAGENO}'],
                ]
            ]);
                
              
            $pdf->output($plantilla,$archivo,'F');
            $url_pdf = \yii\helpers\Url::to(['convenio-pago/down-pdf','name'=>$nombre_archivo]);                
            Yii::$app->response->format = 'json';
            return ['result_error' => '0', 'result_texto' => $url_pdf];
                
        }
        catch(Exception $e)
        {
            Yii::$app->response->format = 'json';
            return ['result_error' => '1', 'result_texto' => 'ERROR AL GENERAR EL ARCHIVO!!!'];
        }
    }//fin PdfConvenio 
   
    
    public function actionDownPdf($name){
        $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
        $archivo = $carp_cont.'/'.$name;
       
        if (is_file($archivo))
        {
            $size = filesize($archivo);
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=convenioPago.pdf");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);     
            readfile($archivo);            
        }
    } // FIN DescargaPdfConvenio
    
    /*************************************************************/
    /*************************************************************/
    
    public function actionEnviarCorreo($id=null){
        try{
            $plantilla = $this->armarPdfconvenio($id);
            $model = $this->findModel($id); 
            
            $carp_cont = Yii::getAlias('@webroot') . "/archivos_generados"; //carpeta a almacenar los archivos
            $nombre_archivo = "convenioPago".Yii::$app->user->id .".pdf";
            $archivo = $carp_cont.'/'.$nombre_archivo;
            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                'format' => Pdf::FORMAT_A4, 
                'orientation' => Pdf::ORIENT_PORTRAIT, 
                'destination' => Pdf::DEST_BROWSER, 
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}', 
                'options' => ['title' => 'Krajee Report Title'],
                'methods' => [ 
                    'SetHeader'=>['Krajee Report Header'], 
                    'SetFooter'=>['{PAGENO}'],
                ]
            ]);     
            $pdf->output($plantilla,$archivo,'F');
            
            $modelFamilia = \app\models\GrupoFamiliar::findOne($model->id_familia);
            $correoCliente = $modelFamilia->responsableD->miPersona->mail;
            $correoCliente = 'arg.gentile@gmail.com';
           
            if(!empty($correoCliente)){
                $mensaje = "Estimado matriculado en el dia de la fecha dio de Alta el Convenio de Pago Nro: ".$model->id;      
                $mensaje .= "<br /> Se adjunta el detalle de su liquidez";      
                
                $name="";
                $description="";                

                if (Yii::$app->mailer->compose('layouts/html',['content' => $mensaje])->setTo($correoCliente)
                        ->setFrom(['no-reply@exalumnosdonboscoviedma.org' => 'Asociaciòn exalumnos de Don Bosco.'])
                        ->setSubject('Detalle Convenio Pago')
                        ->attach($archivo)
                        ->send()){
                    if (Yii::$app->request->isAjax){  
                        Yii::$app->response->format = 'json';
                        return ['result_error' => '0', 'result_texto' => 'ENVIO CORREO CORRECTO!!!'];
                    }else
                        return true;
                }else{
                    if (Yii::$app->request->isAjax){  
                        Yii::$app->response->format = 'json';
                        return ['result_error' => '1', 'result_texto' => 'ERROR AL ENVIAR EL CORREO!!!'];
                    }else
                        return false;
                }
            }else
                if (Yii::$app->request->isAjax){  
                    Yii::$app->response->format = 'json';
                    return ['result_error' => '1', 'result_texto' => 'ERROR AL ENVIAR EL CORREO!!!'];
                }else
                    return false;
        }catch(Exception $e)
        {
            if (Yii::$app->request->isAjax){  
                    Yii::$app->response->format = 'json';
                    return ['result_error' => '1', 'result_texto' => 'ERROR AL ENVIAR EL CORREO!!!'];
                }else
                    return false;
        }
        
        
    }
    
}
