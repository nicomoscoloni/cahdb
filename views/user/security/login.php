<?php



/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

use app\assets\AppAsset;

use dektrium\user\widgets\Connect;
use yii\widgets\ActiveForm;

AppAsset::register($this);
?>
<?php $this->beginPage();
      $this->title = 'Asosiación Hermanos de Don Bosco';?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                
                <div class="box box-login">
                   
                    <div class="panel-body">
                        <p class="text-center"> <img src="<?php echo Yii::getAlias('@web') . "/images/logo.jpg"; ?>" alt="" title="" /></p>
                        <?php $form = ActiveForm::begin([
                            'id'                     => 'login-form',
                            'enableAjaxValidation'   => true,
                            'enableClientValidation' => false,
                            'validateOnBlur'         => false,
                            'validateOnType'         => false,
                            'validateOnChange'       => false,
                        ]) ?>

                        <?= $form->field(
                            $model,
                            'login',
                            ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                        ) ?>

                        <?= $form
                            ->field(
                                $model,
                                'password',
                                ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']]
                            )
                            ->passwordInput()
                            ->label(
                                Yii::t('user', 'Password')
                                .($module->enablePasswordRecovery ?
                                    ' (' . Html::a(
                                        Yii::t('user', 'Forgot password?'),
                                        ['/user/recovery/request'],
                                        ['tabindex' => '5']
                                    )
                                    . ')' : '')
                            ) ?>

                        <?= Html::submitButton(
                            Yii::t('user', 'Sign in'),
                            ['class' => 'btn btn-primary btn-block btn-login', 'tabindex' => '3']
                        ) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <?php if ($module->enableConfirmation): ?>
                    <p class="text-center">
                        <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
                    </p>
                <?php endif ?>
               
                <?= Connect::widget([
                    'baseAuthUrl' => ['/user/security/auth'],
                ]) ?>
            </div>
        </div>
    </div>
</div>



<?php $this->endBody() ?>
    <style type="text/css">
     .btn-login{background-color: #8e101b !important;
     color: #fff !important;
     border: none !important;}
     body{background-color: #d2d6de;}
     .box-login{background-color: #fff !important;}
     .text-center{text-align: center;}
    </style>
</body>
</html>
<?php $this->endPage() ?>