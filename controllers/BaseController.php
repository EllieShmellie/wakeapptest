<?php 
namespace wake\controllers;

use app\repositories\NotFoundException;
use dekor\ArrayToTextTable;
use yii\console\Controller;
use wake\services\CurrencyService;

class BaseController extends Controller{

    private $service;

    public function __construct($id, $module, CurrencyService $service, $config = [])
    {

        $this->service = $service;
        parent::__construct($id, $module, $config);
    }
 

    public function actionList()
    {
        $result = $this->service->getCurrencyArray();
        echo 'Last Update: '.$result['date']."\n";
        echo (new ArrayToTextTable($result['Valute']))->render();

    }

    public function actionExchange(string $symcode, string $otherSymCode, $amount){
        $currency = $this->service->GetBySymCode($symcode);
        $otherCurrency = $this->service->getBySymCode($otherSymCode);
        if ($currency != null && $otherCurrency != null)
            echo $this->service->exchange($currency, $otherCurrency, $amount);
        else
            throw new NotFoundException();
    }
}