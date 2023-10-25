<?php

namespace Rector\Tests\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector\Fixture;

class SkipEnscaped
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
