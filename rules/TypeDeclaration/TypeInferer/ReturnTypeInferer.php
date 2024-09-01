<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer\ReturnedNodesReturnTypeInfererTypeInferer;
use Rector\TypeDeclaration\TypeNormalizer;

/**
 * @internal
 */
final readonly class ReturnTypeInferer
{
    public function __construct(
        private TypeNormalizer $typeNormalizer,
        private ReturnedNodesReturnTypeInfererTypeInferer $returnedNodesReturnTypeInfererTypeInferer
    ) {
    }

    public function inferFunctionLike(ClassMethod|Function_|Closure $functionLike): Type
    {
        $originalType = $this->returnedNodesReturnTypeInfererTypeInferer->inferFunctionLike($functionLike);
        if ($originalType instanceof MixedType) {
            return new MixedType();
        }

        return $this->typeNormalizer->normalizeArrayTypeAndArrayNever($originalType);
    }
}
