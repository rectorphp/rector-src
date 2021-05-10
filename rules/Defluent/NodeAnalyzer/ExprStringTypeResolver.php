<?php

declare(strict_types=1);

namespace Rector\Defluent\NodeAnalyzer;

use PhpParser\Node\Expr;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;

final class ExprStringTypeResolver
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
        private TypeUnwrapper $typeUnwrapper
    ) {
    }

    public function resolve(Expr $expr): ?string
    {
        $exprStaticType = $this->nodeTypeResolver->getStaticType($expr);
        $exprStaticType = $this->typeUnwrapper->unwrapNullableType($exprStaticType);

        if (! $exprStaticType instanceof TypeWithClassName) {
            // nothing we can do, unless
            return null;
        }

        if ($exprStaticType instanceof AliasedObjectType) {
            return $exprStaticType->getFullyQualifiedClass();
        }

        return $exprStaticType->getClassName();
    }
}
