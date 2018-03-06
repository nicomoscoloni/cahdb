<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\assets\GrupoFamiliarAsset;
GrupoFamiliarAsset::register($this);
?>

<div class="dropdown" id="drop-menu-grupofamiliar">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Opciones
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
        <li>
        <?php    
            if(Yii::$app->user->can('cargarFamilia')){
                echo Html::a('<i class="fa fa-pencil"></i> Actualizar', ['actualizar', 'id' => $model->id], ['class' => '']);
            } ?>
        </li>
        <li>
        <?php
            if(Yii::$app->user->can('eliminarFamilia')){
                echo Html::a('<i class="fa fa-trash-o"></i>  Eliminar', ['delete', 'id' => $model->id], [
                    'class' => '',
                    'data' => [
                        'confirm' => 'Está seguro que desea realizar la eliminación?',
                        'method' => 'post',
                    ],
                ]); 
            }?>
        </li>              
        <li role="separator" class="divider"></li>
        <li>
            <?= Html::a('<i class="fa fa-users"></i> Integrantes', ['view', 'id' => $model->id], ['class' => '']); ?>                    
        </li>
        <li>
            <?= Html::a('<i class="fa fa-pencil"></i> Servicios', ['servicios-familia', 'familia' => $model->id], ['class' => '']); ?>
        </li>
    </ul>
</div>


<div class="row" id="datafamiliar"> <!-- row dettales delconvenio -->
    <div class="col-sm-8  col-xs-12">
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