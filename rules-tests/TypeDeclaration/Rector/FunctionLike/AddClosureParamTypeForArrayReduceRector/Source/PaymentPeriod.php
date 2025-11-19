<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Source;

interface PaymentPeriod {
    public function getSum() : float|null;
}
