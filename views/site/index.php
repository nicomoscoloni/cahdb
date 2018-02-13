<?php
$connection=Yii::$app->db;

$sql_alumnos = "SELECT count(*) FROM alumno WHERE activo='1'";
$total_alumnos =  $connection->createCommand($sql_alumnos)->queryScalar();

$sql_alumnosmorosos = "SELECT count(DISTINCT(id_alumno)) FROM servicio_alumno sa INNER JOIN servicio_ofrecido so ON (so.id = sa.id_servicio) WHERE (so.fecha_vencimiento < NOW()) and sa.estado='A' ";
$total_alumnosmorosos =  $connection->createCommand($sql_alumnosmorosos)->queryScalar();


/* @var $this yii\web\View */

$this->title = 'Hermanos Don Bosco';
?>

<div class="site-index">
<div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo $total_alumnos; ?></h3>
          <p> ALUMNOS </p>
        </div>
        <div class="icon">
          <i class="fa fa-user-plus"></i>
        </div>
        <a href="<?php echo \yii\helpers\Url::to(["alumno/listado"]); ?>" class="small-box-footer">M&aacute;s Informacion <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo $total_alumnosmorosos; ?></h3>
          <p> SERVICIOS IMPAGOS </p>
        </div>
        <div class="icon">
          <i class="fa fa-angle-down"></i>
        </div>
          <a href="<?php echo  \yii\helpers\Url::to(["servicio-alumno/servicios-impagos"]); ?>" class="small-box-footer">M&aacute;s Informacion <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    
</div>
</div>