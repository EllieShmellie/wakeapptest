<?php

$db = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';
return [
    'id' => 'wakeapptest',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'wake\bootstrap\Bootstrap'
     ],
    'controllerNamespace' => 'wake\controllers',
    'aliases' => [
        '@wake' => dirname(__DIR__),
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'db' =>$db,
        'assetManager' => [
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color'],
                ],
            ],
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@app/assets/source/bootstrap',
                    'css' => [
                        'css/bootstrap.less'
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@app/assets/source/bootstrap',
                ],
                'yii\bootstrap\BootstrapThemeAsset' => [
                    'sourcePath' => '@app/assets/source/bootstrap',
                ],
            ],
        ],
    ]
];