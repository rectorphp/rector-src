<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node;
use PhpParser\Node\Param;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\TypeAnalyzer\GenericClassStringTypeNormalizer;
use Rector\TypeDeclaration\TypeInferer\ParamTypeInferer\FunctionLikeDocParamTypeInferer;

final class ParamTypeInferer
{
    public function __construct(
        private readonly GenericClassStringTypeNormalizer $genericClassStringTypeNormalizer,
        private readonly FunctionLikeDocParamTypeInferer $functionLikeDocParamTypeInferer,
        private readonly NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function inferParam(Param $param): Type
    {
        $paramType = $this->functionLikeDocParamTypeInferer->inferParam($param);
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
}
