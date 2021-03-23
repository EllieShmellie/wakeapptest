<?php
namespace wake\entities\Currency;

class Code{
    private $number;
    private $symbol;
    public function __construct(int $numCode, string $symCode)
    {
        //add assertion for both field;
        $this->number = $numCode;
        $this->symbol = $symCode;
    }

    public function getNumberCode(){
        return $this->number;
    }

    public function getSymbolCode(){
        return $this->symbol;
    }
}