<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\Scope;
use PHPStan\Node\Expr\PropertyInitializationExpr;
use PHPStan\Node\MethodReturnStatementsNode;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * Answers "is this property initialized on every path out of this class method?",
 * backed by the same PHPStan flow analysis that powers its uninitialized property rules
 *
 * @see \Rector\Tests\NodeAnalyzer\AlwaysInitializedPropertyAnalyzerTest
 */
final class AlwaysInitializedPropertyAnalyzer
{
    public function isAlwaysInitialized(ClassMethod $classMethod, string $propertyName): bool
    {
        $executionEndScope = $this->resolveExecutionEndScope($classMethod);
        if (! $executionEndScope instanceof Scope) {
            return false;
        }

        return $executionEndScope->hasExpressionType(new PropertyInitializationExpr($propertyName))
            ->yes();
    }

    /**
     * Scope merged from every execution end and return statement, so it describes
     * what is certain once the class method has finished, no matter which path was taken
     */
    private function resolveExecutionEndScope(ClassMethod $classMethod): ?MutatingScope
    {
        $methodReturnStatementsNode = $classMethod->getAttribute(AttributeKey::METHOD_RETURN_STATEMENTS_NODE);
        if (! $methodReturnStatementsNode instanceof MethodReturnStatementsNode) {
            return null;
        }

        $executionEndScope = null;

        foreach ($methodReturnStatementsNode->getExecutionEnds() as $executionEndNode) {
            $executionEndScope = $this->mergeScopes(
                $executionEndScope,
                $executionEndNode->getStatementResult()
                    ->getScope()
            );
        }

        foreach ($methodReturnStatementsNode->getReturnStatements() as $returnStatement) {
            $executionEndScope = $this->mergeScopes($executionEndScope, $returnStatement->getScope());
        }

        return $executionEndScope;
    }

    private function mergeScopes(?MutatingScope $mutatingScope, Scope $scope): ?MutatingScope
    {
        if (! $scope instanceof MutatingScope) {
            return $mutatingScope;
        }

        if (! $mutatingScope instanceof MutatingScope) {
            return $scope;
        }

        return $mutatingScope->mergeWith($scope);
    }
}
