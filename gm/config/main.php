<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-gm',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'gm\controllers',
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Shanghai',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-gm',
        ],
        'user' => [
            'identityClass' => 'common\models\Agent',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-gm', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the gm
            'name' => 'advanced-gm',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=101.132.79.78;dbname=squirrelsr',//正式
//            'dsn' => 'mysql:host=120.55.46.138;dbname=squirrelsr',
            'username' => 'root',
            'password' => 'root',
//            'username' => 'squirrelsr',
//            'password' => '123456',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true, // 开启schema缓存(true)  关闭缓存(false)
            'schemaCacheDuration' => 3600, // 缓存有效时间
            // 用来存储 schema 信息的缓存组件名称
            'schemaCache' => 'cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '120.55.46.138',//'101.132.79.78',
            'port' => 6379,
            'database' => 0,
            'password' => '123456',
        ],
    ],
    'params' => $params,
];
