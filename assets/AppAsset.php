<?php
namespace wake\assets;
use yii\web\AssetBundle;

class  AppAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset', // this line
    ];
}