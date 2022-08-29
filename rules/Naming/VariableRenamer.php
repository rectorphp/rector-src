<?php

declare(strict_types=1);

namespace Rector\Naming;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\PhpDoc\VarTagValueNodeRenamer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class VariableRenamer
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly VarTagValueNodeRenamer $varTagValueNodeRenamer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function renameVariableInFunctionLike(
        ClassMethod | Function_ | Closure | ArrowFunction $functionLike,
        string $oldName,
        string $expectedName,
        ?Assign $assign = null
    ): void {
        $isRenamingActive = false;

        if ($assign === null) {
            $isRenamingActive = true;
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->getStmts(),
            function (Node $node) use ($oldName, $expectedName, $assign, &$isRenamingActive): ?Variable {
                if ($assign !== null && $node === $assign) {
                    $isRenamingActive = true;
                    return null;
                }

                if (! $node instanceof Variable) {
                    return null;
                }

                // skip param names
                $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
                if ($parentNode instanceof Param) {
                    return null;
                }

                // TODO: Remove in next PR (with above param check?),
                // TODO: Should be implemented in BreakingVariableRenameGuard::shouldSkipParam()
                if ($this->isParamInParentFunction($node)) {
                    return null;
                }

                if (! $isRenamingActive) {
                    return null;
                }

                return $this->renameVariableIfMatchesName($node, $oldName, $expectedName);
            }
        );
    }

    private function isParamInParentFunction(Variable $variable): bool
    {
        $closure = $this->betterNodeFinder->findParentType($variable, Closure::class);
        if (! $closure instanceof Closure) {
            return false;
        }

        $variableName = $this->nodeNameResolver->getName($variable);
        if ($variableName === null) {
            return false;
        }

        foreach ($closure->params as $param) {
            if ($this->nodeNameResolver->isName($param, $variableName)) {
                return true;
            }
        }

        return false;
    }

    private function renameVariableIfMatchesName(Variable $variable, string $oldName, string $expectedName): ?Variable
    {
        if (! $this->nodeNameResolver->isName($variable, $oldName)) {
            return null;
        }

        $variable->name = $expectedName;

        $variablePhpDocInfo = $this->resolvePhpDocInfo($variable);
        $this->varTagValueNodeRenamer->renameAssignVarTagVariableName($variablePhpDocInfo, $oldName, $expectedName);

        return $variable;
    }

    /**
     * Expression doc block has higher priority
     */
    private function resolvePhpDocInfo(Variable $variable): PhpDocInfo
    {
        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($variable);
        if ($currentStmt instanceof Node) {
            return $this->phpDocInfoFactory->createFromNodeOrEmpty($currentStmt);
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($variable);
    }
}
