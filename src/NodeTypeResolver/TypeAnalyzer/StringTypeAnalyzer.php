<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node\Expr;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class StringTypeAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function isStringOrUnionStringOnlyType(Expr $expr): bool
    {
        $nodeType = $this->nodeTypeResolver->getType($expr);
        return $nodeType->isString()
            ->yes();
    }
}
