<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Throw_;
use PHPStan\Analyser\Scope;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeNestingScope\ValueObject\ControlStructure;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ClassModifierChecker;
use Rector\TypeDeclaration\NodeAnalyzer\NeverFuncCallAnalyzer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;

final readonly class AddNeverReturnType
{
    public function __construct(
        private ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private ClassModifierChecker $classModifierChecker,
        private BetterNodeFinder $betterNodeFinder,
        private NeverFuncCallAnalyzer $neverFuncCallAnalyzer,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function add(ClassMethod|Function_|Closure $node, Scope $scope): ClassMethod|Function_|Closure|null
    {
        if ($this->shouldSkip($node, $scope)) {
            return null;
        }

        $node->returnType = new Identifier('never');

        return $node;
    }

    private function shouldSkip(ClassMethod | Function_ | Closure $node, Scope $scope): bool
    {
        // already has return type, and non-void
        // it can be "never" return itself, or other return type
        if ($node->returnType instanceof Node && ! $this->nodeNameResolver->isName($node->returnType, 'void')) {
            return true;
        }

        if ($this->hasReturnOrYields($node)) {
            return true;
        }

        if (! $this->hasNeverNodesOrNeverFuncCalls($node)) {
            return true;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return true;
        }

        if (! $node->returnType instanceof Node) {
            return false;
        }

        // skip as most likely intentional
        return ! $this->classModifierChecker->isInsideFinalClass($node) && $this->nodeNameResolver->isName(
            $node->returnType,
            'void'
        );
    }

    private function hasReturnOrYields(ClassMethod|Function_|Closure $node): bool
    {
        return $this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped(
            $node,
            [Return_::class, Yield_::class, YieldFrom::class, ...ControlStructure::CONDITIONAL_NODE_SCOPE_TYPES]
        );
    }

    private function hasNeverNodesOrNeverFuncCalls(ClassMethod|Function_|Closure $node): bool
    {
        $hasNeverNodes = $this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped($node, [Throw_::class]);
        if ($hasNeverNodes) {
            return true;
        }

        return $this->neverFuncCallAnalyzer->hasNeverFuncCall($node);
    }
}
