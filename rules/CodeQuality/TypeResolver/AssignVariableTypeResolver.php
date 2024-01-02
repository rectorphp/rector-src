<?php

declare(strict_types=1);

namespace Rector\CodeQuality\TypeResolver;

use PhpParser\Node\Expr\Assign;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class AssignVariableTypeResolver
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function resolve(Assign $assign): Type
    {
        $exprType = $this->nodeTypeResolver->getType($assign->expr);
        if ($exprType instanceof UnionType) {
            return $exprType;
        }

        return $this->nodeTypeResolver->getType($assign->var);
    }
}
