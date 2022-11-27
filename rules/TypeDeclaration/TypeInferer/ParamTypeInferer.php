<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\TypeAnalyzer\GenericClassStringTypeNormalizer;

final class ParamTypeInferer
{
    public function __construct(
        private readonly GenericClassStringTypeNormalizer $genericClassStringTypeNormalizer,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function resolveClassMethodParamDocType(ClassMethod $classMethod, Param $param): Type
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

        $paramTypesByName = $phpDocInfo->getParamTypesByName();
        if ($paramTypesByName === []) {
            return new MixedType();
        }

        $paramType = $this->matchParamNodeFromDoc($paramTypesByName, $param);
        if ($paramType instanceof MixedType) {
            return new MixedType();
        }

        $inferedType = $this->genericClassStringTypeNormalizer->normalize($paramType);
        if ($param->default instanceof Node) {
            $paramDefaultType = $this->nodeTypeResolver->getType($param->default);
            if (! $paramDefaultType instanceof $inferedType) {
                return new MixedType();
            }
        }

        return $inferedType;
    }

    /**
     * @param Type[] $paramWithTypes
     */
    private function matchParamNodeFromDoc(array $paramWithTypes, Param $param): Type
    {
        $paramNodeName = '$' . $this->nodeNameResolver->getName($param->var);
        return $paramWithTypes[$paramNodeName] ?? new MixedType();
    }
}
