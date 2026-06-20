<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddParamTypeToRefactorMethodRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;

final class SkipNonRectorClass
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
    }
}
