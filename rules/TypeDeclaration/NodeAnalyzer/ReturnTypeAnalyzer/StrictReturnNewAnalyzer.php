<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\TypeDeclaration\ValueObject\AssignToVariable;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;

final class StrictReturnNewAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly ReturnAnalyzer $returnAnalyzer
    ) {
    }

    public function matchAlwaysReturnVariableNew(ClassMethod|Closure|Function_ $functionLike): ?string
    {
        if ($functionLike->stmts === null) {
            return null;
        }

        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped($functionLike, [Yield_::class])) {
            return null;
        }

        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Return_::class);
        if ($returns === []) {
            return null;
        }

        // is one statement depth 3?
        if (! $this->returnAnalyzer->areExclusiveExprReturns($returns)) {
            return null;
        }

        // has root return?
        if (! $this->returnAnalyzer->hasClassMethodRootReturn($functionLike)) {
            return null;
        }

        if (count($returns) !== 1) {
            return null;
        }

        // exact one return of variable
        $onlyReturn = $returns[0];
        if (! $onlyReturn->expr instanceof Variable) {
            return null;
        }

        $returnType = $this->nodeTypeResolver->getType($onlyReturn->expr);

        if (! $returnType instanceof ObjectType) {
            return null;
        }

        $createdVariablesToTypes = $this->resolveCreatedVariablesToTypes($functionLike);

        $returnedVariableName = $this->nodeNameResolver->getName($onlyReturn->expr);

        return $this->resolveClassName($returnType, $createdVariablesToTypes, $returnedVariableName);
    }

    /**
     * @param string[] $createdVariablesToTypes
     */
    private function resolveClassName(
        ObjectType $objectType,
        array $createdVariablesToTypes,
        ?string $returnedVariableName
    ): ?string {
        $className = $createdVariablesToTypes[$returnedVariableName] ?? null;
        if (! is_string($className)) {
            return $className;
        }

        if ($objectType->getClassName() === $className) {
            return $className;
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    private function resolveCreatedVariablesToTypes(ClassMethod|Function_|Closure $functionLike): array
    {
        $createdVariablesToTypes = [];

        // what new is assigned to it?
        foreach ((array) $functionLike->stmts as $stmt) {
            $assignToVariable = $this->matchAssignToVariable($stmt);
            if (! $assignToVariable instanceof AssignToVariable) {
                continue;
            }

            $assignedExpr = $assignToVariable->getAssignedExpr();
            $variableName = $assignToVariable->getVariableName();

            if (! $assignedExpr instanceof New_) {
                // possible variable override by another type! - unset it
                if (isset($createdVariablesToTypes[$variableName])) {
                    unset($createdVariablesToTypes[$variableName]);
                }

                continue;
            }

            $className = $this->nodeNameResolver->getName($assignedExpr->class);
            if (! is_string($className)) {
                continue;
            }

            $createdVariablesToTypes[$variableName] = $className;
        }

        return $createdVariablesToTypes;
    }

    private function matchAssignToVariable(Stmt $stmt): ?AssignToVariable
    {
        if (! $stmt instanceof Expression) {
            return null;
        }

        if (! $stmt->expr instanceof Assign) {
            return null;
        }

        $assign = $stmt->expr;
        $assignedVar = $assign->var;

        if (! $assignedVar instanceof Variable) {
            return null;
        }

        $variableName = $this->nodeNameResolver->getName($assignedVar);
        if (! is_string($variableName)) {
            return null;
        }

        return new AssignToVariable($variableName, $assign->expr);
    }
}
