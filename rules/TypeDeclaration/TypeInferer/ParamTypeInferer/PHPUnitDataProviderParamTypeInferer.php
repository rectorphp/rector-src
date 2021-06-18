<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\ParamTypeInferer;

use Nette\Utils\Strings;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\TypeDeclaration\Contract\TypeInferer\ParamTypeInfererInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class PHPUnitDataProviderParamTypeInferer implements ParamTypeInfererInterface
{
    /**
     * @see https://regex101.com/r/hW09Vt/1
     * @var string
     */
    private const METHOD_NAME_REGEX = '#^(?<method_name>\w+)(\(\))?#';

    private NodeTypeResolver $nodeTypeResolver;

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private TypeFactory $typeFactory,
        private PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    // Prevents circular reference

    #[Required]
    public function autowirePHPUnitDataProviderParamTypeInferer(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    public function inferParam(Param $param): Type
    {
        $dataProviderClassMethod = $this->resolveDataProviderClassMethod($param);
        if (! $dataProviderClassMethod instanceof ClassMethod) {
            return new MixedType();
        }

        $parameterPosition = $param->getAttribute(AttributeKey::PARAMETER_POSITION);
        if ($parameterPosition === null) {
            return new MixedType();
        }

        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstanceOf((array) $dataProviderClassMethod->stmts, Return_::class);
        if ($returns !== []) {
            return $this->resolveReturnStaticArrayTypeByParameterPosition($returns, $parameterPosition);
        }

        /** @var Yield_[] $yields */
        $yields = $this->betterNodeFinder->findInstanceOf((array) $dataProviderClassMethod->stmts, Yield_::class);
        return $this->resolveYieldStaticArrayTypeByParameterPosition($yields, $parameterPosition);
    }

    private function resolveDataProviderClassMethod(Param $param): ?ClassMethod
    {
        $phpDocInfo = $this->getFunctionLikePhpDocInfo($param);

        $phpDocTagNode = $phpDocInfo->getByName('@dataProvider');
        if (! $phpDocTagNode instanceof PhpDocTagNode) {
            return null;
        }

        $classLike = $param->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            return null;
        }

        if (! $phpDocTagNode->value instanceof GenericTagValueNode) {
            return null;
        }

        $content = $phpDocTagNode->value->value;
        $match = Strings::match($content, self::METHOD_NAME_REGEX);
        if ($match === null) {
            return null;
        }

        $methodName = $match['method_name'];
        return $classLike->getMethod($methodName);
    }

    /**
     * @param Return_[] $returns
     */
    private function resolveReturnStaticArrayTypeByParameterPosition(array $returns, int $parameterPosition): Type
    {
        $firstReturnedExpr = $returns[0]->expr;

        if (! $firstReturnedExpr instanceof Array_) {
            throw new ShouldNotHappenException();
        }

        $paramOnPositionTypes = $this->resolveParamOnPositionTypes($firstReturnedExpr, $parameterPosition);
        if ($paramOnPositionTypes === []) {
            return new MixedType();
        }

        return $this->typeFactory->createMixedPassedOrUnionType($paramOnPositionTypes);
    }

    /**
     * @param Yield_[] $yields
     */
    private function resolveYieldStaticArrayTypeByParameterPosition(array $yields, int $parameterPosition): Type
    {
        $paramOnPositionTypes = [];

        foreach ($yields as $yield) {
            if (! $yield->value instanceof Array_) {
                continue;
            }

            $type = $this->getTypeFromClassMethodYield($yield->value);

            if (! $type instanceof ConstantArrayType) {
                return $type;
            }

            foreach ($type->getValueTypes() as $position => $valueType) {
                if ($position !== $parameterPosition) {
                    continue;
                }

                $paramOnPositionTypes[] = $valueType;
            }
        }

        if ($paramOnPositionTypes === []) {
            return new MixedType();
        }

        return $this->typeFactory->createMixedPassedOrUnionType($paramOnPositionTypes);
    }

    private function getTypeFromClassMethodYield(Array_ $classMethodYieldArrayNode): MixedType | ConstantArrayType
    {
        $arrayTypes = $this->nodeTypeResolver->resolve($classMethodYieldArrayNode);

        // impossible to resolve
        if (! $arrayTypes instanceof ConstantArrayType) {
            return new MixedType();
        }

        return $arrayTypes;
    }

    private function getFunctionLikePhpDocInfo(Param $param): PhpDocInfo
    {
        $parent = $param->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof FunctionLike) {
            throw new ShouldNotHappenException();
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($parent);
    }

    /**
     * @return Type[]
     */
    private function resolveParamOnPositionTypes(Array_ $array, int $parameterPosition): array
    {
        $paramOnPositionTypes = [];

        foreach ($array->items as $singleDataProvidedSet) {
            if (! $singleDataProvidedSet instanceof ArrayItem || ! $singleDataProvidedSet->value instanceof Array_) {
                throw new ShouldNotHappenException();
            }

            foreach ($singleDataProvidedSet->value->items as $position => $singleDataProvidedSetItem) {
                if ($position !== $parameterPosition) {
                    continue;
                }
                if (! $singleDataProvidedSetItem instanceof ArrayItem) {
                    continue;
                }

                $paramOnPositionTypes[] = $this->nodeTypeResolver->resolve($singleDataProvidedSetItem->value);
            }
        }

        return $paramOnPositionTypes;
    }
}
