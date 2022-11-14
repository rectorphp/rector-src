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

    public function isStringOrUnionStringOnlyType(Expr $expr): bool
    {
        $nodeType = $this->nodeTypeResolver->getType($expr);
        return $nodeType->isString()->yes();
    }
}
