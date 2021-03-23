<?php
namespace wake\services;

use wake\entities\Currency\Currency;
use wake\entities\Currency\Code;
use wake\entities\Currency\Id;
use wake\helpers\Exchange;
use wake\repositories\ICurrencyRepository;

class CurrencyService{
    private $repository;
    private $exchanger;
    
    function __construct(ICurrencyRepository $repository, Exchange $exchange)
    {
        $this->repository = $repository;
        $this->exchanger = $exchange;
    }

    private function refreshRates(){
        /** @var Currency[] $list */
        $list = $this->repository->getList();
    
        if(empty($list) || end($list)->getCurrentRate()->getDate()->diff(new \DateTimeImmutable())->days > 0){
            $rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'), true);
            $list = [];
            foreach($rates["Valute"] as $key => $item){
                $tmp= new Currency(Id::next(), new Code($item['NumCode'], $item['CharCode']),
                $item['Name'],
                $item['Value'], 
                $item['Nominal'],
                new \DateTimeImmutable());
                $this->repository->add($tmp);
            }
        }
    }
    
    /**
     *
     * @return Currency[]
     */
    public function getCurrencyList(){
        $this->refreshRates();
        return $this->repository->getList();
    }

    public function getCurrencyArray(){
        $list = $this->getCurrencyList();
        $result = [];
        /** @var Currency $item */
        foreach($list as $item){
            $result[] = ['NumCode' => $item->getCode()->getNumberCode(),
             'CharCode' => $item->getCode()->getSymbolCode(), 
             'Name' => $item->getTitle(), 
             'Nominal' => $item->getCurrentRate()->getUnit(),
             'Value' => $item->getCurrentRate()->getValue(),
            ];
            if(count($item->getRates()) > 1 ){
                $result['Trend'] = $item->isGreater() ? '▲' : '▼';
            }
        }
        return ['date' => end($list)->getCurrentRate()->getDate()->format('d-m-Y H:i:s'), 'Valute' => $result];
    }
    public function exchange(Currency $currency, Currency $targetCurrency, float $amount){
        $this->refreshRates();
        return $this->exchanger->__invoke($currency, $targetCurrency, $amount);
    }

    public function getBySymCode(string $code){
        foreach($this->getCurrencyList() as $currency){
            if(strtoupper($code) == $currency->getCode()->getSymbolCode())
                return $currency;
        }
    }

}