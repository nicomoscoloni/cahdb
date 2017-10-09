<?php

/*
 *  This file is part of the Goitel project.
 * @author Sebastian Hernandorena
 */

namespace app\controllers\user;

use dektrium\user\controllers\SecurityController as BaseSecurityController;
use dektrium\user\models\LoginForm;

class SecurityController extends BaseSecurityController
{

    public function actionLogin()
    {
       
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            return $this->goBack();
        }

        return $this->renderPartial('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }
}
