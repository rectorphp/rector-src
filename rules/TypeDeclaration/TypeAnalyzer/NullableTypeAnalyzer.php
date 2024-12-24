<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class NullableTypeAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
    ) {
    }

    public function resolveNullableObjectType(Expr $expr): ObjectType|null
    {
        if ($expr instanceof Assign) {
            return null;
        }

        $exprType = $this->nodeTypeResolver->getNativeType($expr);

        $baseType = TypeCombinator::removeNull($exprType);
        if (! $baseType instanceof ObjectType) {
            return null;
        }

        return $baseType;
    }
}
