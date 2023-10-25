<?php

namespace Rector\Tests\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector\Fixture;

class SkipPhpdoc
{
    /**
     * @var int
     */
    public $adresseid_lieferung;

    public function run()
    {
        if ('' == $this->adresseid_lieferung) {
        }
    }
}
