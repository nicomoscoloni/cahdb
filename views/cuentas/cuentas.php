<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CajaCashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Cuentas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-colegio">
       <div class="box-header with-border">        
           <i class="fa fa-weibo"></i> <h3 class="box-title"> Administración Cuentas </h3> 
      </div>    
      <div class="box-body">
          
        <div class="col-lg-4 col-xs-12">        
          <div class="small-box box-bg-tribunales">
            <div class="inner">
              <h3>$ <?php echo $modelCuentaColegio->saldo_actual; ?></h3>
              <p> CAJA COELGIO </p>
            </div>
            <div class="icon">
                <i class="ion ion-social-usd"></i>
            </div>
              <a href="<?php echo yii\helpers\Url::to(["cuentas/resumen-cuenta","id"=>$modelCuentaColegio->id]); ?>" class="small-box-footer">Detalle Cuenta <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
          
        <div class="col-lg-4 col-xs-12">
        <!-- small box -->
          <div class="small-box box-bg-belgrano">
            <div class="inner">
               <h3>$ <?php echo $modelCuentaPatagonia->saldo_actual; ?></h3>
               <p> CAJA PATAGONIA </p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
              
            <a href="<?php echo yii\helpers\Url::to(["cuentas/resumen-cuenta","id"=>$modelCuentaPatagonia->id]); ?>" class="small-box-footer">Detalle Cuenta <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
          
         
    
     
</div>
<style type="text/css">
.box-bg-tribunales{background-color: rgba(0, 166, 158, 0.85) !important;}
.box-bg-belgrano{background-color: rgba(0, 128, 166, 0.45) !important;}
.box-bg-patagonia{background-color: rgba(5, 166, 89, 0.63) !important;  }
.box-bg-rio{background-color: rgba(0, 128, 166, 0.65) !important;
}
</style>
          
           
      </div>
</div>