<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'es',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JDYUKa5FjvgQTIF4tkk0vnZCVTtpPhpg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [                
            'identityClass' => 'dektrium\user\models\User',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'arg.gentile@gmail.com',
                'password' => 'nrgmldp???',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d-m-Y',
            'thousandSeparator' => '.',
            'decimalSeparator' => ',',
            'numberFormatterSymbols' => [
                NumberFormatter::CURRENCY_SYMBOL => '$',
            ]            
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'db' => $db,
        
        'errorHandler' => [           
            'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',           
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'modules' => [   
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'user' => [
            'class' => 'dektrium\user\Module',
                'enableRegistration'=>true,
                'enableFlashMessages'=>true,            
                'enableConfirmation'=>false,
                'enablePasswordRecovery'=>true,
                'admins'=>['agusAdmins','admins'],
                'controllerMap' => [
                    'security' => 'app\controllers\user\SecurityController',
                    'recovery' => 'app\controllers\user\RecoveryController',
                    'registration' => 'app\controllers\user\RegistrationController'
                ],
        ],
        'audit' => 
        [
          'class'=>'bedezign\yii2\audit\Audit',
          'ignoreActions'=>['audit/*','debug/*'],
            'accessUsers'=>null,
            'accessRoles'=>['adminaudit'],
        ]
    ],
    
    'params' => $params,
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login', 'register','request','logout','devengar-servicio'],
                'allow' => true,             
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['user/security/login']);
         },
    ],     
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
