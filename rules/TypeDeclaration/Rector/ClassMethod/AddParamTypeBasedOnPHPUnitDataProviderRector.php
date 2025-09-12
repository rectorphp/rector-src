<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\DataProviderNodes;
use Rector\TypeDeclarationDocblocks\NodeFinder\DataProviderMethodsFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\AddParamTypeBasedOnPHPUnitDataProviderRectorTest
 */
final class AddParamTypeBasedOnPHPUnitDataProviderRector extends AbstractRector
{
    /**
     * @var string
     */
    private const ERROR_MESSAGE = 'Adds param type declaration based on PHPUnit provider return type declaration';

    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly TestsNodeAnalyzer $testsNodeAnalyzer,
        private readonly DataProviderMethodsFinder $dataProviderMethodsFinder,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StaticTypeMapper $staticTypeMapper,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test($value)
    {
    }

    public static function provideData()
    {
        yield ['name'];
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $value)
    {
    }

    public static function provideData()
    {
        yield ['name'];
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if (! $classMethod->isPublic()) {
                continue;
            }

            if ($classMethod->getParams() === []) {
                continue;
            }

            $dataProviderNodes = $this->dataProviderMethodsFinder->findDataProviderNodes($node, $classMethod);
            if ($dataProviderNodes->getClassMethods() === []) {
                continue;
            }

            $hasClassMethodChanged = $this->refactorClassMethod($classMethod, $dataProviderNodes);
            if ($hasClassMethodChanged) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function inferParam(int $parameterPosition, ClassMethod $dataProviderClassMethod): Type
    {
        $returns = $this->betterNodeFinder->findReturnsScoped($dataProviderClassMethod);
        if ($returns !== []) {
            return $this->resolveReturnStaticArrayTypeByParameterPosition($returns, $parameterPosition);
        }

        /** @var Yield_[] $yields */
        $yields = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($dataProviderClassMethod, Yield_::class);
        return $this->resolveYieldStaticArrayTypeByParameterPosition($yields, $parameterPosition);
    }

    /**
     * @param Return_[] $returns
     */
    private function resolveReturnStaticArrayTypeByParameterPosition(array $returns, int $parameterPosition): Type
    {
        $firstReturnedExpr = $returns[0]->expr;

        if (! $firstReturnedExpr instanceof Array_) {
            return new MixedType();
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

    private function getTypeFromClassMethodYield(Array_ $classMethodYieldArray): MixedType | ConstantArrayType
    {
        $arrayType = $this->nodeTypeResolver->getType($classMethodYieldArray);

        // impossible to resolve
        if (! $arrayType instanceof ConstantArrayType) {
            return new MixedType();
        }

        return $arrayType;
    }

    /**
     * @return Type[]
     */
    private function resolveParamOnPositionTypes(Array_ $array, int $parameterPosition): array
    {
        $paramOnPositionTypes = [];

        foreach ($array->items as $singleDataProvidedSet) {
            if (! $singleDataProvidedSet instanceof ArrayItem || ! $singleDataProvidedSet->value instanceof Array_) {
                return [];
            }

            foreach ($singleDataProvidedSet->value->items as $position => $singleDataProvidedSetItem) {
                if ($position !== $parameterPosition) {
                    continue;
                }

                if (! $singleDataProvidedSetItem instanceof ArrayItem) {
                    continue;
                }

                $paramOnPositionTypes[] = $this->nodeTypeResolver->getType($singleDataProvidedSetItem->value);
            }
        }

        return $paramOnPositionTypes;
    }

    private function refactorClassMethod(ClassMethod $classMethod, DataProviderNodes $dataProviderNodes): bool
    {
        $hasChanged = false;

        foreach ($classMethod->getParams() as $parameterPosition => $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            if ($param->variadic) {
                continue;
            }

            $paramTypes = [];
            foreach ($dataProviderNodes->getClassMethods() as $dataProviderClassMethod) {
                $paramTypes[] = $this->inferParam($parameterPosition, $dataProviderClassMethod);
            }

            $paramTypeDeclaration = TypeCombinator::union(...$paramTypes);
            if ($paramTypeDeclaration instanceof MixedType) {
                continue;
            }

            $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($paramTypeDeclaration, TypeKind::PARAM);
            if ($typeNode instanceof Node) {
                $param->type = $typeNode;
                $hasChanged = true;
            }
        }

        return $hasChanged;
    }
}
