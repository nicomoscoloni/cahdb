<?php 
use kartik\widgets\ActiveForm;
use dmstr\helpers\Html;

?>
<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
<div class="box box-warning">
    <div class="box-header with-border">
        <i class="glyphicon glyphicon-search"></i><h3 class="box-title"> Busqueda </h3>
        <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">  
        <div id="form-cobroservicio">
            <?php $form = ActiveForm::begin(['id'=>'form-abogado']); ?>   

            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($searchModel, 'apellidos',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
                </div>
           
                <div class="col-sm-4">
                    <?= $form->field($searchModel, 'folio',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
                </div>                
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($searchModel, 'responsable',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
                </div>       
            </div>
            
            <div class="form-group">
                <?= Html::submitButton('<i class=\'fa fa-search\'></i> Buscar', 
                    ['class' => 'btn btn-primary', 'id'=>'btn-busqueda']) ?>
            </div>  
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>