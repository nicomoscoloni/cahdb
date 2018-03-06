<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BonificacionAlumnoController implements the CRUD actions for BonificacionAlumno model.
 */
class AyudaController extends Controller
{
    public function actionVideosTutoriales(){
        return $this->render('videos');
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
    
}

