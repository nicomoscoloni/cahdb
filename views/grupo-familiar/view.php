<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;



/* @var $this yii\web\View */
/* @var $model common\models\GrupoFamiliar */

$this->title = "Grupo Familiar: " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Familiars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
    yii\bootstrap\Modal::begin([        
        'id'=>'modalAsignacionResponsable',
        'header'=>'Asignación de Responsable',
        'class' =>'modal-scrollbar', 
        'size' => 'modal-lg',
        ]);
        echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end();
?>

<div class="box box-solid box-colegio" id="grupo-familiar-index">
    <div class="box-header with-border">
        <i class="fa fa-users"></i> <h3 class="box-title"> FAMILIA Nº <?= $model->id;?> </h3>       
    </div>
    
    <div class="box-body">
        <?php echo $this->render('_viewDatosFamilia', ['model' => $model]); ?>
        
        <?php echo $this->render('_viewIntegrantes', ['dataProviderResponsables' => $dataProviderResponsables,
                                'dataProviderAlumnos' => $dataProviderAlumnos,
                                'familia'=>$model->id   
                               ]); ?>
        
    </div> <!-- box body -->
</div>