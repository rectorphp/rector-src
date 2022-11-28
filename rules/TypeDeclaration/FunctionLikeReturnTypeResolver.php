<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class FunctionLikeReturnTypeResolver
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function resolveFunctionLikeReturnTypeToPHPStanType(ClassMethod $classMethod): Type
    {
        $functionReturnType = $classMethod->getReturnType();
        if ($functionReturnType === null) {
            return new MixedType();
        }

        return $this->staticTypeMapper->mapPhpParserNodePHPStanType($functionReturnType);
    }
}
