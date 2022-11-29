<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;
use Rector\TypeDeclaration\Guard\PropertyTypeOverrideGuard;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\TrustedClassMethodPropertyTypeInferer;
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
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly PropertyTypeOverrideGuard $propertyTypeOverrideGuard,
        private readonly ReflectionProvider $reflectionProvider,
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
        $hasChanged = false;

        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return null;
        }

        foreach ($node->getProperties() as $property) {
            if ($this->shouldSkipProperty($property, $node)) {
                continue;
            }

            $propertyType = $this->trustedClassMethodPropertyTypeInferer->inferProperty(
                $property,
                $constructClassMethod
            );

            if ($propertyType instanceof MixedType) {
                continue;
            }

            if ($propertyType instanceof ObjectType && $propertyType->isInstanceOf(
                'Doctrine\Common\Collections\Collection'
            )->yes()) {
                continue;
            }

            if (! $this->propertyTypeOverrideGuard->isLegal($property)) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

            // public property can be anything
            if ($this->isVarDocPreffered($property)) {
                $this->phpDocTypeChanger->changeVarType($phpDocInfo, $propertyType);
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

            if (! $property->isPublic()) {
                $property->type = $propertyTypeNode;
            }

            $propertyName = $this->nodeNameResolver->getName($property);

            if ($this->constructorAssignDetector->isPropertyAssigned($node, $propertyName)) {
                $property->props[0]->default = null;
            }

            $this->varTagRemover->removeVarTagIfUseless($phpDocInfo, $property);
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

    /**
     * @return string[]
     */
    private function resolveTraitPropertyNames(Class_ $class): array
    {
        $traitPropertyNames = [];

        foreach ($class->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $traitName) {
                $traitNameString = $this->getName($traitName);
                if (! $this->reflectionProvider->hasClass($traitNameString)) {
                    continue;
                }

                $traitClassReflection = $this->reflectionProvider->getClass($traitNameString);
                $nativeReflection = $traitClassReflection->getNativeReflection();
                foreach ($nativeReflection->getProperties() as $property) {
                    $traitPropertyNames[] = $property->getName();
                }
            }
        }

        return $traitPropertyNames;
    }

    private function shouldSkipProperty(Property $property, Class_ $class): bool
    {
        if ($property->type !== null) {
            return true;
        }

        $traitPropertyNames = $this->resolveTraitPropertyNames($class);
        return $this->isNames($property, $traitPropertyNames);
    }

    private function isVarDocPreffered(Property $property): bool
    {
        if ($property->isPublic()) {
            return true;
        }

        return ! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES);
    }
}
