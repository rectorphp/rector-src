<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;

final class SkipReassignCast
{
    /**
     * @param Int_|Float_ $node
     */
    public function refactor(Node $node)
    {
        $node->value = (string) $node->value;
        if ($node instanceof Float_ && ! \str_contains($node->value, '.')) {

        }
    }
}
