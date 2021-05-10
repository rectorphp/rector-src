<?php

declare(strict_types=1);

namespace Rector\Defluent\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class GetterMethodCallAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function isGetterMethodCall(MethodCall $methodCall): bool
    {
        if ($methodCall->var instanceof MethodCall) {
            return false;
        }

        $methodCallStaticType = $this->nodeTypeResolver->getStaticType($methodCall);
        $methodCallVarStaticType = $this->nodeTypeResolver->getStaticType($methodCall->var);

        // getter short call type
        return ! $methodCallStaticType->equals($methodCallVarStaticType);
    }
}
