<?php
namespace wake\bootstrap;

use ElisDN\Hydrator\Hydrator;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use wake\helpers\Exchange;
use wake\repositories\CurrencyRepository;
use wake\repositories\ICurrencyRepository;
use yii\base\BootstrapInterface;
use yii\di\Instance;

class Bootstrap implements BootstrapInterface{

    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $container->setSingleton('db', function () use ($app) {
            return $app->db;
        });
        $container->setSingleton(Hydrator::class);
        $container->setSingleton(LazyLoadingValueHolderFactory::class);
        $container->setSingleton(ICurrencyRepository::class ,CurrencyRepository::class, [Instance::of('db')]);
        $container->setSingleton(Exchange::class);
    }


}