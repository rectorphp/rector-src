<?php

declare(strict_types=1);

namespace Rector\Defluent\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PHPStan\Type\MixedType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class NewFluentChainMethodCallNodeAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function isNewMethodCallReturningSelf(MethodCall $methodCall): bool
    {
        $newStaticType = $this->nodeTypeResolver->getStaticType($methodCall->var);
        $methodCallStaticType = $this->nodeTypeResolver->getStaticType($methodCall);

        return $methodCallStaticType->equals($newStaticType);
    }

    /**
     * Method call with "new X", that returns "X"?
     * e.g.
     *
     * $this->setItem(new Item) // → returns "Item"
     */
    public function matchNewInFluentSetterMethodCall(MethodCall $methodCall): ?New_
    {
        if (count($methodCall->args) !== 1) {
            return null;
        }

        if (! isset($methodCall->args[0])) {
            return null;
        }

        if (! $methodCall->args[0] instanceof Arg) {
            return null;
        }

        $onlyArgValue = $methodCall->args[0]->value;
        if (! $onlyArgValue instanceof New_) {
            return null;
        }

        $newType = $this->nodeTypeResolver->resolve($onlyArgValue);
        if ($newType instanceof MixedType) {
            return null;
        }

        $parentMethodCallReturnType = $this->nodeTypeResolver->resolve($methodCall);
        if (! $newType->equals($parentMethodCallReturnType)) {
            return null;
        }

        return $onlyArgValue;
    }
}
