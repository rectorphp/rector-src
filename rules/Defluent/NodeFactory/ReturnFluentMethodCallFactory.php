<?php

declare(strict_types=1);

namespace Rector\Defluent\NodeFactory;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Defluent\NodeAnalyzer\FluentChainMethodCallRootExtractor;
use Rector\Defluent\ValueObject\FirstAssignFluentCall;
use Rector\Defluent\ValueObject\FluentMethodCalls;
use Rector\Naming\Naming\PropertyNaming;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ReturnFluentMethodCallFactory
{
    public function __construct(
        private FluentChainMethodCallRootExtractor $fluentChainMethodCallRootExtractor,
        private NodeTypeResolver $nodeTypeResolver,
        private PropertyNaming $propertyNaming
    ) {
    }

    public function createFromFluentMethodCalls(FluentMethodCalls $fluentMethodCalls): ?FirstAssignFluentCall
    {
        $rootMethodCall = $fluentMethodCalls->getRootMethodCall();

        // this means the 1st method creates different object then it runs on
        // e.g. $sheet->getRow(), creates a "Row" object
        $isFirstMethodCallFactory = $this->fluentChainMethodCallRootExtractor->isFirstMethodCallFactory(
            $rootMethodCall
        );

        $lastMethodCall = $fluentMethodCalls->getRootMethodCall();

        if ($lastMethodCall->var instanceof PropertyFetch) {
            $assignExpr = $lastMethodCall->var;
        } else {
            // we need a variable to assign the stuff into
            // the method call, does not belong to the
            $staticType = $this->nodeTypeResolver->getStaticType($rootMethodCall);
            if (! $staticType instanceof ObjectType) {
                return null;
            }

            $variableName = $this->propertyNaming->fqnToVariableName($staticType);
            $assignExpr = new Variable($variableName);
        }

        return new FirstAssignFluentCall(
            $assignExpr,
            $rootMethodCall,
            $isFirstMethodCallFactory,
            $fluentMethodCalls
        );
    }
}
