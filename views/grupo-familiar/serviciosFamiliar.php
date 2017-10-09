<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

use app\assets\grupoFamiliar;
grupoFamiliar::register($this); 


/* @var $this yii\web\View */
/* @var $model common\models\GrupoFamiliar */

$this->title = "Grupo Familiar: " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Familiars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-solid box-colegio" id="grupo-familiar-index">
    <div class="box-header with-border">
        <i class="fa fa-users"></i> <h3 class="box-title"> FAMILIA Nº <?= $model->id;?> </h3>       
    </div>
    
    <div class="box-body">
        <?php echo $this->render('_viewDatosFamilia', ['model' => $model]); ?>
    
        <?= app\widgets\buscadorServiciosAlumno\BuscadorServiciosAlumno::widget(['searchModel' => $searchModel,'dataProvider'=>$dataProvider,'buscador'=>false]);
                        ?>
    </div>
</div>