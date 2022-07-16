<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node\Expr;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class StringTypeAnalyzer
{
    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function isStringOrUnionStringOnlyType(Expr $node): bool
    {
        $nodeType = $this->nodeTypeResolver->getType($node);
        if ($nodeType instanceof StringType) {
            return true;
        }

        if ($nodeType instanceof UnionType) {
            foreach ($nodeType->getTypes() as $singleType) {
                if ($singleType->isSuperTypeOf(new StringType())->no()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
