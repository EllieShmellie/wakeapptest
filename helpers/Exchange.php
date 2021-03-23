<?php
namespace wake\helpers;

use wake\entities\Currency\Currency;

class Exchange{
    function __invoke(Currency $currency, Currency $targetCurrency, float $amount){
        return $currency->getCurrentRate()->getValue() * $amount / $targetCurrency->getCurrentRate()->getValue();
    }
}