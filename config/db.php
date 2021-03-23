<?php

return  [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=postgres',
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',
    'attributes' => [

        \PDO::ATTR_EMULATE_PREPARES => true,
    ],
];