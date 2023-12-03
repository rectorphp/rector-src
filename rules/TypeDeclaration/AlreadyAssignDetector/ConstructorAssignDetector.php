<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\AlreadyAssignDetector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\TypeDeclaration\Matcher\PropertyAssignMatcher;
use Rector\TypeDeclaration\NodeAnalyzer\AutowiredClassMethodOrPropertyAnalyzer;

final class ConstructorAssignDetector
{
    /**
     * @var string
     */
    private const IS_FIRST_LEVEL_STATEMENT = 'first_level_stmt';

    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly PropertyAssignMatcher $propertyAssignMatcher,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly AutowiredClassMethodOrPropertyAnalyzer $autowiredClassMethodOrPropertyAnalyzer,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
    ) {
    }

    public function isPropertyAssigned(ClassLike $classLike, string $propertyName): bool
    {
        $initializeClassMethods = $this->matchInitializeClassMethod($classLike);
        if ($initializeClassMethods === []) {
            return false;
        }

        $isAssignedInConstructor = false;

        $this->decorateFirstLevelStatementAttribute($initializeClassMethods);

        foreach ($initializeClassMethods as $initializeClassMethod) {
            $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $initializeClassMethod->stmts, function (
                Node $node
            ) use ($propertyName, &$isAssignedInConstructor): ?int {
                if ($this->isIfElseAssign($node, $propertyName)) {
                    $isAssignedInConstructor = true;
                    return NodeTraverser::STOP_TRAVERSAL;
                }

                $expr = $this->matchAssignExprToPropertyName($node, $propertyName);
                if (! $expr instanceof Expr) {
                    return null;
                }

                /** @var Assign $assign */
                $assign = $node;
                $isFirstLevelStatement = $assign->getAttribute(self::IS_FIRST_LEVEL_STATEMENT);

                // cannot be nested
                if ($isFirstLevelStatement !== true) {
                    return null;
                }

                $isAssignedInConstructor = true;

                return NodeTraverser::STOP_TRAVERSAL;
            });
        }

        if (! $isAssignedInConstructor) {
            return $this->propertyFetchAnalyzer->isFilledViaMethodCallInConstructStmts($classLike, $propertyName);
        }

        return $isAssignedInConstructor;
    }


    private function isIfElseAssign(Node $node, string $propertyName): bool
    {
        if ($node instanceof If_
            && $node->elseifs === []
            && $node->else instanceof Else_) {
            $isInIfStmt = false;
            foreach ($node->stmts as $stmt) {
                if (! $stmt instanceof Expression) {
                    return false;
                }

                if ($this->matchAssignExprToPropertyName($stmt->expr, $propertyName)) {
                    $isInIfStmt = true;
                    break;
                }
            }

            $isInElseStmt = false;
            foreach ($node->else->stmts as $stmt) {
                if (! $stmt instanceof Expression) {
                    return false;
                }

                if ($this->matchAssignExprToPropertyName($stmt->expr, $propertyName)) {
                    $isInElseStmt = true;
                    break;
                }
            }

            return $isInIfStmt && $isInElseStmt;
        }

        return false;
    }

    private function matchAssignExprToPropertyName(Node $node, string $propertyName): ?Expr
    {
        if (! $node instanceof Assign) {
            return null;
        }

        return $this->propertyAssignMatcher->matchPropertyAssignExpr($node, $propertyName);
    }

    /**
     * @param ClassMethod[] $classMethods
     */
    private function decorateFirstLevelStatementAttribute(array $classMethods): void
    {
        foreach ($classMethods as $classMethod) {
            foreach ((array) $classMethod->stmts as $methodStmt) {
                $methodStmt->setAttribute(self::IS_FIRST_LEVEL_STATEMENT, true);

                if ($methodStmt instanceof Expression) {
                    $methodStmt->expr->setAttribute(self::IS_FIRST_LEVEL_STATEMENT, true);
                }
            }
        }
    }

    /**
     * @return ClassMethod[]
     */
    private function matchInitializeClassMethod(ClassLike $classLike): array
    {
        $initializingClassMethods = [];

        $constructClassMethod = $classLike->getMethod(MethodName::CONSTRUCT);
        if ($constructClassMethod instanceof ClassMethod) {
            $initializingClassMethods[] = $constructClassMethod;
        }

        $testCaseObjectType = new ObjectType('PHPUnit\Framework\TestCase');
        if ($this->nodeTypeResolver->isObjectType($classLike, $testCaseObjectType)) {
            $setUpClassMethod = $classLike->getMethod(MethodName::SET_UP);
            if ($setUpClassMethod instanceof ClassMethod) {
                $initializingClassMethods[] = $setUpClassMethod;
            }

            $setUpBeforeClassMethod = $classLike->getMethod(MethodName::SET_UP_BEFORE_CLASS);
            if ($setUpBeforeClassMethod instanceof ClassMethod) {
                $initializingClassMethods[] = $setUpBeforeClassMethod;
            }
        }

        foreach ($classLike->getMethods() as $classMethod) {
            if (! $this->autowiredClassMethodOrPropertyAnalyzer->detect($classMethod)) {
                continue;
            }

            $initializingClassMethods[] = $classMethod;
        }

        return $initializingClassMethods;
    }
}
