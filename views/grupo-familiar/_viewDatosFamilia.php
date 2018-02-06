<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\assets\GrupoFamiliarAsset;
GrupoFamiliarAsset::register($this);
?>

<p>
    <?php    
    if(Yii::$app->user->can('cargarFamilia')){
        echo Html::a('<i class="fa fa-pencil"></i> Actualizar', ['actualizar', 'id' => $model->id], ['class' => 'btn btn-primary']);
    } ?>
    
    <?php
    if(Yii::$app->user->can('eliminarFamilia')){
        echo Html::a('<i class="fa fa-trash-o"></i>  Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea realizar la eliminación?',
                'method' => 'post',
            ],
        ]); 
    }?>
    
    <?php
        echo Html::a('<i class="fa fa-users"></i> Integrantes', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
    
    <?= Html::a('<i class="fa fa-pencil"></i> Servicios', ['servicios-familia', 'familia' => $model->id], ['class' => 'btn btn-primary']); ?>
</p>
        
<div class="row"> <!-- row dettales delconvenio -->
    <div class="col-sm-8 col-sm-offset-2 col-xs-12">
        <table>
            <tr>
                <td width="30%"> 
                    <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/family.png"; ?>" alt="familia" />  
                </td>
                <td width="60%">
                    <h3 class="text-light-blue bold">    Nro Familia / Folio: <?php echo $model->folio; ?> </h3>
                    <span class="text-light-blue bold">  Apellido/s: </span> <?php echo $model->apellidos; ?> <br />
                    <span class="text-light-blue bold">  Descripción: </span> <?php echo $model->descripcion; ?> <br />
                    <span class="text-light-blue bold">  Pago Asociado: </span> <?php echo $model->pagoAsociado->nombre; ?> <br />
                 </td>
            </tr>
        </table>  
    </div>
</div>