<?php
namespace wake\entities\Currency;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;

class Id{
    private $value;

    public function __construct(string $id)
    {
        Assertion::notEmpty($id);
 
        $this->value = $id;
    }
    
    public function getId(){
        return $this->value;
    }
    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}