<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;
use Rector\TypeDeclaration\Guard\PropertyTypeOverrideGuard;
use Rector\TypeDeclaration\TypeAnalyzer\PropertyTypeDefaultValueAnalyzer;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\TrustedClassMethodPropertyTypeInferer;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\TypedPropertyFromStrictConstructorRectorTest
 */
final class TypedPropertyFromStrictConstructorRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly TrustedClassMethodPropertyTypeInferer $trustedClassMethodPropertyTypeInferer,
        private readonly VarTagRemover $varTagRemover,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly ConstructorAssignDetector $constructorAssignDetector,
        private readonly PropertyTypeOverrideGuard $propertyTypeOverrideGuard,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private readonly PropertyTypeDefaultValueAnalyzer $propertyTypeDefaultValueAnalyzer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add typed properties based only on strict constructor types', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeObject
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeObject
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod || $node->getProperties() === []) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->getProperties() as $property) {
            if (! $this->propertyTypeOverrideGuard->isLegal($property, $classReflection)) {
                continue;
            }

            $propertyType = $this->trustedClassMethodPropertyTypeInferer->inferProperty(
                $node,
                $property,
                $constructClassMethod
            );

            if ($this->shouldSkipPropertyType($propertyType)) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

            // public property can be anything
            if ($property->isPublic()) {
                if (! $phpDocInfo->getVarType() instanceof MixedType) {
                    continue;
                }

                $this->phpDocTypeChanger->changeVarType($property, $phpDocInfo, $propertyType);
                $hasChanged = true;
                continue;
            }

            $propertyTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $propertyType,
                TypeKind::PROPERTY
            );
            if (! $propertyTypeNode instanceof Node) {
                continue;
            }

            $propertyProperty = $property->props[0];

            $propertyName = $this->nodeNameResolver->getName($property);
            if ($this->constructorAssignDetector->isPropertyAssigned($node, $propertyName)) {
                $propertyProperty->default = null;
                $hasChanged = true;
            }

            if ($this->propertyTypeDefaultValueAnalyzer->doesConflictWithDefaultValue(
                $propertyProperty,
                $propertyType
            )) {
                continue;
            }

            $property->type = $propertyTypeNode;
            $this->varTagRemover->removeVarTagIfUseless($phpDocInfo, $property);

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_PROPERTIES;
    }

    private function shouldSkipPropertyType(Type $propertyType): bool
    {
        if ($propertyType instanceof MixedType) {
            return true;
        }

        return $this->doctrineTypeAnalyzer->isInstanceOfCollectionType($propertyType);
    }
}
