<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use DateTimeInterface;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\UnionType;
use Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final readonly class ExpectedNameResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private PropertyNaming $propertyNaming,
        private MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver
    ) {
    }

    public function resolveForParamIfNotYet(Param $param): ?string
    {
        if ($param->type instanceof UnionType) {
            return null;
        }

        $expectedName = $this->matchParamTypeExpectedNameResolver->resolve($param);
        if ($expectedName === null) {
            return null;
        }

        $currentName = $this->nodeNameResolver->getName($param);
        if ($currentName === $expectedName || str_ends_with($currentName, ucfirst($expectedName))) {
            return null;
        }

        return $expectedName;
    }

    public function resolveForAssignNonNew(Assign $assign): ?string
    {
        if ($assign->expr instanceof New_) {
            return null;
        }

        if (! $assign->var instanceof Variable) {
            return null;
        }

        $variable = $assign->var;

        return $this->nodeNameResolver->getName($variable);
    }

    public function resolveForAssignNew(Assign $assign): ?string
    {
        if (! $assign->expr instanceof New_) {
            return null;
        }

        if (! $assign->var instanceof Variable) {
            return null;
        }

        $new = $assign->expr;
        if (! $new->class instanceof Name) {
            return null;
        }

        $className = $this->nodeNameResolver->getName($new->class);
        $fullyQualifiedObjectType = new FullyQualifiedObjectType($className);

        if ($fullyQualifiedObjectType->isInstanceOf(DateTimeInterface::class)->yes()) {
            return null;
        }

        $expectedName = $this->propertyNaming->getExpectedNameFromType($fullyQualifiedObjectType);
        if (! $expectedName instanceof ExpectedName) {
            return null;
        }

        return $expectedName->getName();
    }
}
