<?php
namespace wake\repositories;

use app\repositories\NotFoundException;
use ElisDN\Hydrator\Hydrator;
use wake\entities\Currency\Code;
use wake\entities\Currency\Currency;
use wake\entities\Currency\Id;
use wake\entities\Currency\Rate;
use yii\db\Connection;
use yii\db\Query;
use yii\helpers\Json;

class CurrencyRepository implements ICurrencyRepository{
    private $db;
    private $hydrator;
    function __construct(Connection $db, Hydrator $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    public function getList(){
        $currencies = (new Query())->select('*')
        ->from('{{%currencies}}')
        ->all($this->db);
        $list = [];
        foreach($currencies as $currency){
            $unit = Json::decode($currency['rates'],true);

            $list[] = $this->hydrator->hydrate(Currency::class, [
                'id' => new Id($currency['id']),
                'code' => new Code(
                    $currency['code_number'],
                    $currency['code_symbol'],
                ),
                'title' => $currency['title'],
                'rates' => array_map(function ($rate) {
                    return new Rate(
                        $rate['unit'],
                        $rate['value'],
                        new \DateTimeImmutable($rate['date'])
                    );
                }, Json::decode($unit,true)),
            ]);
        }
        return $list;
    }
    public function get(Id $id){
        $currency = (new Query())->select('*')
        ->from('{{%currencies}}')
        ->andWhere(['id' => $id->getId()])
        ->one($this->db);

    if (!$currency) {
        throw new NotFoundException('Currency not found.');
    }
    
    return $this->hydrator->hydrate(Currency::class, [
        'id' => new Id($currency['id']),
        'code' => new Code(
            $currency['code_number'],
            $currency['code_symbol'],
        ),
        'title' => $currency['title'],
        'rates' => array_map(function ($rate) {
            return new Rate(
                $rate['unit'],
                $rate['value'],
                new \DateTimeImmutable($rate['date'])
            );
        }, Json::decode($currency['rates'])),
    ]);
    }
    public function add(Currency $currency){
        $this->db->transaction(function () use ($currency) {
            $this->db->createCommand()
                ->insert('{{%currencies}}', self::extractData($currency))
                ->execute();
        });
    }
    public function refresh(Currency $currency){
        $this->db->transaction(function () use ($currency) {
            $this->db->createCommand()
                ->update(
                    '{{%currencies}}',
                    self::extractData($currency),
                    ['id' => $currency->getId()->getId()]
                )->execute();
        });
    }
    /**
     * @param Currency[] $currencies
     * @return void
     */
    public function refreshAll(array $currencies){

        $list = [];
        foreach($currencies as $currency){
            $list[] = array_values(self::extractData($currency));
        }
        
        $this->db->transaction(function () use($list){
            $sql = $this->db->queryBuilder->batchInsert('{{%currencies}}', ['id', 'code_number', 'code_symbol', 'title', 'rates'], $list);
            $this->db->createCommand($sql . ' ON CONFLICT (code_number) DO UPDATE SET rates = excluded.rates')->execute();
        });
    }
    public function remove(Currency $currency): void
    {
        $this->db->createCommand()
            ->delete('{{%currencies}}', ['id' => $currency->getId()->getId()])
            ->execute();
    }

    private static function extractData(Currency $currency)
    {
        $rates = $currency->getRates();

        return [
            'id' => $currency->getId()->getId(),
            'code_number' => $currency->getCode()->getNumberCode(),
            'code_symbol' => $currency->getCode()->getSymbolCode(),
            'title' => $currency->getTitle(),
            'rates' => Json::encode(array_map(function (Rate $rate) {
                return [
                    'unit' => $rate->getUnit(),
                    'value' => $rate->getValue(),
                    'date' => $rate->getDate()->format(DATE_RFC3339),
                ];
            }, $currency->getRates())),
        ];
    }
}