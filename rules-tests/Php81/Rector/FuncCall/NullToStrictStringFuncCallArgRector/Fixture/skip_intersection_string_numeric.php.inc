<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;

final class SkipIntersectionStringNumeric
{
    /**
     * @param Int_|Float_ $node
     */
    public function refactor(Node $node)
    {
        $numericValueAsString = (string) $node->value;

        if (\str_contains($numericValueAsString, '.')) {
            [$mainPart, $decimalPart] = explode('.', $numericValueAsString);
        }
    }
}
