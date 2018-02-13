<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ServicioAlumnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicio Alumnos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> <h3 class="box-title"> Establecimientos </h3>       
    </div>
    <div class="box-body">         
        <?php echo app\widgets\buscadorServiciosAlumno\BuscadorServiciosAlumno::widget(['searchModel' => $searchModel,'dataProvider'=>$dataProvider]); ?>
    </div>
</div>
    
