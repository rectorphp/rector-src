<?php

declare(strict_types=1);

namespace Rector\Transform\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\ObjectType;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class SingletonClassMethodAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
        private ValueResolver $valueResolver,
        private NodeComparator $nodeComparator
    ) {
    }

    /**
     * Match this code:
     * if (null === static::$instance) {
     *     static::$instance = new static();
     * }
     * return static::$instance;
     *
     * Matches "static::$instance" on success
     */
    public function matchStaticPropertyFetch(ClassMethod $classMethod): ?StaticPropertyFetch
    {
        $stmts = (array) $classMethod->stmts;
        if (count($stmts) !== 2) {
            return null;
        }

        $firstStmt = $stmts[0] ?? null;
        if (! $firstStmt instanceof If_) {
            return null;
        }

        $staticPropertyFetch = $this->matchStaticPropertyFetchInIfCond($firstStmt->cond);

        if (count($firstStmt->stmts) !== 1) {
            return null;
        }

        if (! $firstStmt->stmts[0] instanceof Expression) {
            return null;
        }

        $stmt = $firstStmt->stmts[0]->expr;

        // create self and assign to static property
        if (! $stmt instanceof Assign) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($staticPropertyFetch, $stmt->var)) {
            return null;
        }

        if (! $stmt->expr instanceof New_) {
            return null;
        }

        /** @var string $class */
        $class = $classMethod->getAttribute(AttributeKey::CLASS_NAME);

        // the "self" class is created
        if (! $this->nodeTypeResolver->isObjectType($stmt->expr->class, new ObjectType($class))) {
            return null;
        }

        /** @var StaticPropertyFetch $staticPropertyFetch */
        return $staticPropertyFetch;
    }

    private function matchStaticPropertyFetchInIfCond(Expr $expr): ?StaticPropertyFetch
    {
        // matching: "self::$static === null"
        if ($expr instanceof Identical) {
            if ($this->valueResolver->isNull($expr->left) && $expr->right instanceof StaticPropertyFetch) {
                return $expr->right;
            }

            if ($this->valueResolver->isNull($expr->right) && $expr->left instanceof StaticPropertyFetch) {
                return $expr->left;
            }
        }

        // matching: "! self::$static"
        if (! $expr instanceof BooleanNot) {
            return null;
        }

        $negatedExpr = $expr->expr;
        if (! $negatedExpr instanceof StaticPropertyFetch) {
            return null;
        }

        return $negatedExpr;
    }
}
