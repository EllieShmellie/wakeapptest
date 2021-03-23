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
    ]
];