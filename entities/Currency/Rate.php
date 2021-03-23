<?php

namespace wake\entities\Currency;

use DateTime;

class Rate{

    private $unit;
    private $value;
    private $date;

    function __construct(float $unit, float $value, \DateTimeImmutable $date){
        $this->unit = $unit;
        $this->value = $value;
        $this->date = $date;
    }

    public function getUnit():float{
        return $this->unit;
    }
    public function getValue():float{
        return $this->value;
    }
    public function getDate(): \DateTimeImmutable{
        return $this->date;
    }
    public function getRate(){
        return $this->unit / $this->value;
    }
}