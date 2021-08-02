<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Matcher;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;

final class PropertyAssignMatcher
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private PropertyFetchAnalyzer $propertyFetchAnalyzer
    ) {
    }

    /**
     * Covers:
     * - $this->propertyName = $expr;
     * - $this->propertyName[] = $expr;
     */
    public function matchPropertyAssignExpr(Assign $assign, string $propertyName): ?Expr
    {
        if ($this->propertyFetchAnalyzer->isPropertyFetch($assign->var)) {
            if (! $this->nodeNameResolver->isName($assign->var, $propertyName)) {
                return null;
            }

            return $assign->expr;
        }

        if ($assign->var instanceof ArrayDimFetch && $this->propertyFetchAnalyzer->isPropertyFetch($assign->var->var)) {
            if (! $this->nodeNameResolver->isName($assign->var->var, $propertyName)) {
                return null;
            }

            return $assign->expr;
        }

        return null;
    }
}
