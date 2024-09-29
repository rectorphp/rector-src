<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\ObjectType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ReflectionResolver;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\TypeDeclaration\ValueObject\AssignToVariable;

final readonly class StrictReturnNewAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private ReturnAnalyzer $returnAnalyzer,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function matchAlwaysReturnVariableNew(ClassMethod|Function_ $functionLike): ?string
    {
        if ($functionLike->stmts === null) {
            return null;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($functionLike);
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($functionLike, $returns)) {
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

        if ($this->isUsedAsParamByRef($functionLike, $onlyReturn->expr)) {
            return null;
        }

        $createdVariablesToTypes = $this->resolveCreatedVariablesToTypes($functionLike);

        $returnedVariableName = $this->nodeNameResolver->getName($onlyReturn->expr);

        $className = $createdVariablesToTypes[$returnedVariableName] ?? null;
        if (! is_string($className)) {
            return $className;
        }

        if ($returnType->getClassName() === $className) {
            return $className;
        }

        return null;
    }

    private function isUsedAsParamByRef(ClassMethod|Function_ $functionLike, Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
            $functionLike,
            function (Node $subNode) use ($variable): bool {
                if (! $subNode instanceof MethodCall && ! $subNode instanceof StaticCall && ! $subNode instanceof FuncCall) {
                    return false;
                }

                if ($subNode->isFirstClassCallable()) {
                    return false;
                }

                $isFound = false;
                foreach ($subNode->getArgs() as $arg) {
                    if ($arg->value instanceof Variable && $arg->value->name === $variable->name) {
                        $isFound = true;
                        break;
                    }
                }

                if (! $isFound) {
                    return false;
                }

                $reflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($subNode);
                if ($reflection === null) {
                    return false;
                }

                $scope = $subNode->getAttribute(AttributeKey::SCOPE);
                if ($scope === null) {
                    return false;
                }

                $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select($reflection, $subNode, $scope);
                foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
                    if ($parameterReflection->passedByReference()) {
                        return true;
                    }
                }

                return false;
            }
        );
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
