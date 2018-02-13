<?php 
use kartik\widgets\ActiveForm;
use dmstr\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>
<div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
<div class="box box-warning">
    <div class="box-header with-border">
       
    </div>
    <div class="box-body">  
        <div id="form-cobroservicio">
            <?php $form = ActiveForm::begin(['id'=>'form-busqueda-movimientos']); ?>   

            <div class="row">
                <div class="col-sm-3">
                    <?=
                    $form->field($model, 'fecha_desde')->widget(
                            DatePicker::className(), ([
                        'language' => 'es',
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]));
                    ?>
                </div>
                <div class="col-sm-3">
                    <?=
                    $form->field($model, 'fecha_hasta')->widget(
                            DatePicker::className(), ([
                        'language' => 'es',
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]));
                    ?>
                </div>
                 <div class="col-sm-3">
                    <?= $form->field($model, 'tipo_movimiento')->dropDownList([''=>'','INGRESO'=>'INGRESO','EGRESO'=>'EGRESO']) ?>
                </div>
                <?= Html::submitButton('<i class=\'fa fa-search\'></i> Buscar', 
                    ['class' => 'btn btn-primary', 'id'=>'btn-busqueda']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-down-listado', 'id'=>'btn-excel',
                        'onclick'=>'js:{downListado("'.Url::to(['cuentas/exportar-excel']) .'");}']) ?>
            </div>
               
            
            
            <div class="form-group">
                
            </div>  
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>