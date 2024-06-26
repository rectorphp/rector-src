<?php

declare(strict_types=1);

namespace Rector\Naming;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Naming\PhpDoc\VarTagValueNodeRenamer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final readonly class VariableRenamer
{
    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private NodeNameResolver $nodeNameResolver,
        private VarTagValueNodeRenamer $varTagValueNodeRenamer,
        private PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function renameVariableInFunctionLike(
        FunctionLike $functionLike,
        string $oldName,
        string $expectedName,
        ?Assign $assign = null
    ): bool {
        $isRenamingActive = false;

        if (! $assign instanceof Assign) {
            $isRenamingActive = true;
        }

        $hasRenamed = false;
        $currentStmt = null;
        $currentFunctionLike = null;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->getStmts(),
            function (Node $node) use (
                $oldName,
                $expectedName,
                $assign,
                &$isRenamingActive,
                &$hasRenamed,
                &$currentStmt,
                &$currentFunctionLike
            ): int|null|Variable {
                // skip param names
                if ($node instanceof Param) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($assign instanceof Assign && $node === $assign) {
                    $isRenamingActive = true;
                    return null;
                }

                if ($node instanceof Stmt) {
                    $currentStmt = $node;
                }

                if ($node instanceof FunctionLike) {
                    $currentFunctionLike = $node;
                }

                if (! $node instanceof Variable) {
                    return null;
                }

                // TODO: Should be implemented in BreakingVariableRenameGuard::shouldSkipParam()
                if ($this->isParamInParentFunction($node, $currentFunctionLike)) {
                    return null;
                }

                if (! $isRenamingActive) {
                    return null;
                }

                $variable = $this->renameVariableIfMatchesName($node, $oldName, $expectedName, $currentStmt);
                if ($variable instanceof Variable) {
                    $hasRenamed = true;
                }

                return $variable;
            }
        );

        return $hasRenamed;
    }

    private function isParamInParentFunction(Variable $variable, ?FunctionLike $functionLike): bool
    {
        if (! $functionLike instanceof FunctionLike) {
            return false;
        }

        $variableName = $this->nodeNameResolver->getName($variable);
        if ($variableName === null) {
            return false;
        }

        foreach ($functionLike->params as $param) {
            if ($this->nodeNameResolver->isName($param, $variableName)) {
                return true;
            }
        }

        return false;
    }

    private function renameVariableIfMatchesName(
        Variable $variable,
        string $oldName,
        string $expectedName,
        ?Stmt $currentStmt
    ): ?Variable {
        if (! $this->nodeNameResolver->isName($variable, $oldName)) {
            return null;
        }

        $variable->name = $expectedName;

        $variablePhpDocInfo = $this->resolvePhpDocInfo($variable, $currentStmt);
        $this->varTagValueNodeRenamer->renameAssignVarTagVariableName($variablePhpDocInfo, $oldName, $expectedName);

        return $variable;
    }

    /**
     * Expression doc block has higher priority
     */
    private function resolvePhpDocInfo(Variable $variable, ?Stmt $currentStmt): PhpDocInfo
    {
        if ($currentStmt instanceof Stmt) {
            return $this->phpDocInfoFactory->createFromNodeOrEmpty($currentStmt);
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($variable);
    }
}
