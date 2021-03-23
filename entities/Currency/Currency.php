<?php
namespace wake\entities\Currency;

use wake\entities\IRoot;

class Currency implements IRoot{

    /**
     * Undocumented variable
     *
     * @var Id
     */
    private $id;
    /**
     * @var Code
     */
    private $code;
    /**
     * @var string
     */
    private $title;
    /**
     * @var Rate[];
     */
    private $rates;

    public function __construct(Id $id, Code $code, string $title, float $rateValue, float $rateUnit, \DateTimeImmutable $date){
        $this->id = $id;
        $this->code = $code;
        $this->title = $title;
        $this->updateRate($rateUnit, $rateValue, $date);
    }
    /**
     * @return Code
     */
    public function getCode(){
        return $this->code;
    }
    /**
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }
    /**
     * @return Rate[]
     */
    public function getRates(){
        return $this->rates;
    }
    /**
     * @return Rate
     */
    public function getCurrentRate(){
        return end($this->rates);
    }
    /**
     * Update current rate
     *
     * @param Rate $newRate
     * @return void
     */
    public function updateRate(float $unit, float $value, \DateTimeImmutable $date){
        $this->rates[] = new Rate($unit, $value, $date);
        $this->currentRate = $value;
    }
    public function getId(){
        return $this->id;
    }
    public function isGreater(){
        return $this->getCurrentRate()->getValue() > array_slice($this->getRates(), -2, 1);
    }
}
