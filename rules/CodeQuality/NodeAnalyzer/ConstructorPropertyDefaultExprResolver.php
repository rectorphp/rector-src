<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\CodeQuality\ValueObject\DefaultPropertyExprAssign;
use Rector\NodeNameResolver\NodeNameResolver;

final class ConstructorPropertyDefaultExprResolver
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @return DefaultPropertyExprAssign[]
     */
    public function resolve(ClassMethod $classMethod): array
    {
        $stmts = $classMethod->getStmts();
        if ($stmts === null) {
            return [];
        }

        $defaultPropertyExprAssigns = [];

        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            $nestedStmt = $stmt->expr;
            if (! $nestedStmt instanceof Assign) {
                continue;
            }

            $assign = $nestedStmt;

            if (! $assign->var instanceof PropertyFetch) {
                continue;
            }

            $propertyFetch = $assign->var;
            if (! $this->nodeNameResolver->isName($propertyFetch->var, 'this')) {
                continue;
            }

            $propertyName = $this->nodeNameResolver->getName($propertyFetch->name);
            if (! is_string($propertyName)) {
                continue;
            }

            $expr = $assign->expr;
            if (! $this->isAllowedPropertyDefaultExpr($expr)) {
                continue;
            }

            $defaultPropertyExprAssigns[] = new DefaultPropertyExprAssign($stmt, $propertyName, $expr);
        }

        return $defaultPropertyExprAssigns;
    }

    private function isAllowedPropertyDefaultExpr(Expr $expr): bool
    {
        if ($expr instanceof Scalar) {
            return true;
        }

        if ($expr instanceof Array_) {
            return $this->isScalarArray($expr);
        }

        return $expr instanceof ConstFetch;
    }

    private function isScalarArray(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if ($item->key instanceof Expr && ! $this->isAllowedPropertyDefaultExpr($item->key)) {
                return false;
            }

            if (! $this->isAllowedPropertyDefaultExpr($item->value)) {
                return false;
            }
        }

        return true;
    }
}
