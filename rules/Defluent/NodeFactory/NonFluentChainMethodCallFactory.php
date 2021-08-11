<?php

declare(strict_types=1);

namespace Rector\Defluent\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use Rector\Defluent\NodeResolver\FirstMethodCallVarResolver;
use Rector\Defluent\ValueObject\AssignAndRootExpr;
use Rector\Defluent\ValueObject\FluentCallsKind;
use Rector\Naming\Naming\VariableNaming;

final class NonFluentChainMethodCallFactory
{
    public function __construct(
        private FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer,
        private VariableNaming $variableNaming,
        private FirstMethodCallVarResolver $firstMethodCallVarResolver
    ) {
    }

    /**
     * @return Expression[]
     */
    public function createFromNewAndRootMethodCall(New_ $new, MethodCall $rootMethodCall): array
    {
        $variableName = $this->variableNaming->resolveFromNode($new);
        if ($variableName === null) {
            throw new ShouldNotHappenException();
        }

        $newVariable = new Variable($variableName);

        $newStmts = [];
        $newStmts[] = $this->createAssignExpression($newVariable, $new);

        // resolve chain calls
        $chainMethodCalls = $this->fluentChainMethodCallNodeAnalyzer->collectAllMethodCallsInChainWithoutRootOne(
            $rootMethodCall
        );

        $chainMethodCalls = array_reverse($chainMethodCalls);
        foreach ($chainMethodCalls as $chainMethodCall) {
            $methodCall = new MethodCall($newVariable, $chainMethodCall->name, $chainMethodCall->args);
            $newStmts[] = new Expression($methodCall);
        }

        return $newStmts;
    }

    /**
     * @param MethodCall[] $chainMethodCalls
     * @return Assign[]|Cast[]|MethodCall[]|Return_[]
     */
    public function createFromAssignObjectAndMethodCalls(
        AssignAndRootExpr $assignAndRootExpr,
        array $chainMethodCalls,
        string $kind,
        ?Node $node = null
    ): array {
        $nodesToAdd = [];

        $isNewNodeNeeded = $this->isNewNodeNeeded($assignAndRootExpr);
        if ($isNewNodeNeeded) {
            $nodesToAdd[] = $assignAndRootExpr->createFirstAssign();
        }

        $decoupledMethodCalls = $this->createNonFluentMethodCalls(
            $chainMethodCalls,
            $assignAndRootExpr,
            $isNewNodeNeeded
        );

        $nodesToAdd = array_merge($nodesToAdd, $decoupledMethodCalls);

        if ($assignAndRootExpr->getSilentVariable() !== null && $kind !== FluentCallsKind::IN_ARGS) {
            $nodesToAdd[] = $assignAndRootExpr->getReturnSilentVariable();
        }

        if ($node instanceof Cast) {
            $lastNodeToAdd = $nodesToAdd[array_key_last($nodesToAdd)];
            $cast = $node::class;
            $nodesToAdd[array_key_last($nodesToAdd)] = new $cast($lastNodeToAdd);
        }

        return $nodesToAdd;
    }

    private function createAssignExpression(Variable $newVariable, New_ $new): Expression
    {
        $assign = new Assign($newVariable, $new);
        return new Expression($assign);
    }

    private function isNewNodeNeeded(AssignAndRootExpr $assignAndRootExpr): bool
    {
        if ($assignAndRootExpr->isFirstCallFactory()) {
            return true;
        }

        if ($assignAndRootExpr->getRootExpr() === $assignAndRootExpr->getAssignExpr()) {
            return false;
        }

        return $assignAndRootExpr->getRootExpr() instanceof New_;
    }

    /**
     * @param MethodCall[] $chainMethodCalls
     * @return Assign[]|MethodCall[]
     */
    private function createNonFluentMethodCalls(
        array $chainMethodCalls,
        AssignAndRootExpr $assignAndRootExpr,
        bool $isNewNodeNeeded
    ): array {
        $decoupledMethodCalls = [];

        $lastKey = array_key_last($chainMethodCalls);

        foreach ($chainMethodCalls as $key => $chainMethodCall) {
            // skip first, already handled
            if ($key === $lastKey && $assignAndRootExpr->isFirstCallFactory() && $isNewNodeNeeded) {
                continue;
            }

            $chainMethodCall->var = $this->firstMethodCallVarResolver->resolve($assignAndRootExpr, $key);
            $decoupledMethodCalls[] = $chainMethodCall;
        }

        if ($assignAndRootExpr->getRootExpr() instanceof New_ && $assignAndRootExpr->getSilentVariable() !== null) {
            $decoupledMethodCalls[] = new Assign(
                $assignAndRootExpr->getSilentVariable(),
                $assignAndRootExpr->getRootExpr()
            );
        }

        return array_reverse($decoupledMethodCalls);
    }
}
