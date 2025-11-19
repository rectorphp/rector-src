<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Source\PaymentPeriod;

final class CombineUnion
{
    /**
     * @param array<PaymentPeriod> $arr
     */
    public function run(array $arr)
    {
        $sumCents = \array_reduce($arr, function ($carry, PaymentPeriod $paymentPeriod) : float|int {
            return $carry + $paymentPeriod->getSum();
        }, 0);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Source\PaymentPeriod;

final class CombineUnion
{
    /**
     * @param array<PaymentPeriod> $arr
     */
    public function run(array $arr)
    {
        $sumCents = \array_reduce($arr, function (int|float $carry, PaymentPeriod $paymentPeriod) : float|int {
            return $carry + $paymentPeriod->getSum();
        }, 0);
    }
}

?>