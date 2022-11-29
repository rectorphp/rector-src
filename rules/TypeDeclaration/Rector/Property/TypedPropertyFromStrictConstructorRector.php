<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
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
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $propertyType = $this->trustedClassMethodPropertyTypeInferer->inferProperty($node, MethodName::CONSTRUCT);
        if (! $propertyType instanceof Type) {
            return null;
        }

        if ($propertyType instanceof MixedType) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentType($node, Class_::class);
        if (! $classLike instanceof Class_) {
            return null;
        }

        if (! $this->propertyTypeOverrideGuard->isLegal($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $propertyType);
            return $node;
        }

        $propertyTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($propertyType, TypeKind::PROPERTY);
        if (! $propertyTypeNode instanceof Node) {
            return null;
        }

        // public property can be anything
        if ($node->isPublic()) {
            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $propertyType);
            return $node;
        }

        $node->type = $propertyTypeNode;
        $propertyName = $this->nodeNameResolver->getName($node);

        if ($this->constructorAssignDetector->isPropertyAssigned($classLike, $propertyName)) {
            $node->props[0]->default = null;
        }

        $this->varTagRemover->removeVarTagIfUseless($phpDocInfo, $node);

        return $node;
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

    private function shouldSkip(Property $property): bool
    {
        if ($property->type !== null) {
            return true;
        }

        $class = $this->betterNodeFinder->findParentType($property, Class_::class);
        if ($class instanceof Class_) {
            $traitPropertyNames = $this->resolveTraitPropertyNames($class);
            if ($this->isNames($property, $traitPropertyNames)) {
                return true;
            }
        }

        return false;
    }
}
