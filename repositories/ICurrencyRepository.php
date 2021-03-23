<?php
namespace wake\repositories;

use wake\entities\Currency\Currency;
use wake\entities\Currency\Id;

interface ICurrencyRepository{
    
    public function getList();
    public function get(Id $id);

    public function add(Currency $currency);
    public function refresh(Currency $currency);
    public function refreshAll(array $currencies);
    public function remove(Currency $currency);
}