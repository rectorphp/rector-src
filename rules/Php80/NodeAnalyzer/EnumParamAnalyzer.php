<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PHPStan\Reflection\ParameterReflectionWithPhpDocs;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Php80\ValueObject\ClassNameAndTagValueNode;

/**
 * Detects enum-like params, e.g.
 * Direction::*
 */
final class EnumParamAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function matchParameterClassName(
        ParameterReflectionWithPhpDocs $parameterReflectionWithPhpDocs,
        PhpDocInfo $phpDocInfo
    ): ?ClassNameAndTagValueNode {
        $paramTagValueNode = $phpDocInfo->getParamTagValueByName($parameterReflectionWithPhpDocs->getName());
        if (! $paramTagValueNode instanceof ParamTagValueNode) {
            return null;
        }

        $className = $this->resolveClassFromConstType($paramTagValueNode->type);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        return new ClassNameAndTagValueNode($className, $paramTagValueNode);
    }

    public function matchReturnClassName(PhpDocInfo $phpDocInfo): ?ClassNameAndTagValueNode
    {
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();
        if (! $returnTagValueNode instanceof ReturnTagValueNode) {
            return null;
        }

        $className = $this->resolveClassFromConstType($returnTagValueNode->type);
        if (! is_string($className)) {
            return null;
        }

        return new ClassNameAndTagValueNode($className, $returnTagValueNode);
    }

    public function matchPropertyClassName(PhpDocInfo $phpDocInfo): ?ClassNameAndTagValueNode
    {
        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            return null;
        }

        $className = $this->resolveClassFromConstType($varTagValueNode->type);
        if (! is_string($className)) {
            return null;
        }

        return new ClassNameAndTagValueNode($className, $varTagValueNode);
    }

    private function resolveClassFromConstType(TypeNode $typeNode): ?string
    {
        if (! $typeNode instanceof ConstTypeNode) {
            return null;
        }

        if (! $typeNode->constExpr instanceof ConstFetchNode) {
            return null;
        }

        $constExpr = $typeNode->constExpr;
        return $constExpr->getAttribute(PhpDocAttributeKey::RESOLVED_CLASS);
    }
}
