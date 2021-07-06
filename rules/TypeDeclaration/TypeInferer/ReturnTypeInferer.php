<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node\FunctionLike;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\ArrayType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface;
use Rector\TypeDeclaration\Sorter\TypeInfererSorter;
use Rector\TypeDeclaration\TypeAnalyzer\GenericClassStringTypeNormalizer;
use Rector\TypeDeclaration\TypeNormalizer;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use PHPStan\Type\ObjectType;

final class ReturnTypeInferer
{
    /**
     * @var ReturnTypeInfererInterface[]
     */
    private array $returnTypeInferers = [];

    /**
     * @param ReturnTypeInfererInterface[] $returnTypeInferers
     */
    public function __construct(
        array $returnTypeInferers,
        private TypeNormalizer $typeNormalizer,
        TypeInfererSorter $typeInfererSorter,
        private GenericClassStringTypeNormalizer $genericClassStringTypeNormalizer,
        private PhpDocInfoFactory $phpDocInfoFactory
    ) {
        $this->returnTypeInferers = $typeInfererSorter->sort($returnTypeInferers);
    }

    public function inferFunctionLike(FunctionLike $functionLike): Type
    {
        return $this->inferFunctionLikeWithExcludedInferers($functionLike, []);
    }

    /**
     * @param array<class-string<ReturnTypeInfererInterface>> $excludedInferers
     */
    public function inferFunctionLikeWithExcludedInferers(FunctionLike $functionLike, array $excludedInferers): Type
    {
        $phpDocInfo           = $this->phpDocInfoFactory->createFromNodeOrEmpty($functionLike);
        $phpDocInfoReturnType = $phpDocInfo->getReturnType();

        foreach ($this->returnTypeInferers as $returnTypeInferer) {
            if ($this->shouldSkipExcludedTypeInferer($returnTypeInferer, $excludedInferers)) {
                continue;
            }

            $originalType = $returnTypeInferer->inferFunctionLike($functionLike);
            if ($originalType instanceof MixedType) {
                continue;
            }

            if ($originalType instanceof UnionType) {
                $isArraySub = false;
                foreach ($originalType->getTypes() as $type) {
                    if ($type instanceof ArrayType && $type->getItemType() instanceof FullyQualifiedObjectType) {
                        if ($phpDocInfoReturnType instanceof ArrayType && $phpDocInfoReturnType->getItemType() instanceof ObjectType) {
                            $isArraySub = $type->getItemType()->isSuperTypeOf($phpDocInfoReturnType->getItemType())->yes();
                            break;
                        }
                    }
                }

                if (! $isArraySub) {
                    return $originalType;
                }
            }

            $type = $this->typeNormalizer->normalizeArrayTypeAndArrayNever($originalType);

            // in case of void, check return type of children methods
            if ($type instanceof MixedType) {
                continue;
            }

            // normalize ConstStringType to ClassStringType
            return $this->genericClassStringTypeNormalizer->normalize($type);
        }

        return new MixedType();
    }

    /**
     * @param array<class-string<ReturnTypeInfererInterface>> $excludedInferers
     */
    private function shouldSkipExcludedTypeInferer(
        ReturnTypeInfererInterface $returnTypeInferer,
        array $excludedInferers
    ): bool {
        foreach ($excludedInferers as $excludedInferer) {
            if (is_a($returnTypeInferer, $excludedInferer)) {
                return true;
            }
        }

        return false;
    }
}
