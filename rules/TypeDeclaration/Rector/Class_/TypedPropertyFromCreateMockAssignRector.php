<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\AllAssignNodePropertyTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector\TypedPropertyFromCreateMockAssignRectorTest
 */
final class TypedPropertyFromCreateMockAssignRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const TEST_CASE_CLASS = 'PHPUnit\Framework\TestCase';

    /**
     * @var string
     */
    private const MOCK_OBJECT_CLASS = 'PHPUnit\Framework\MockObject\MockObject';

    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly AllAssignNodePropertyTypeInferer $allAssignNodePropertyTypeInferer,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ConstructorAssignDetector $constructorAssignDetector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add typed property from assigned mock', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    private $someProperty;

    protected function setUp(): void
    {
        $this->someProperty = $this->createMock(SomeMockedClass::class);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $someProperty;

    protected function setUp(): void
    {
        $this->someProperty = $this->createMock(SomeMockedClass::class);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, new ObjectType(self::TEST_CASE_CLASS))) {
            return null;
        }

        $classReflection = null;
        $hasChanged = false;

        foreach ($node->getProperties() as $property) {
            // already typed
            if ($property->type instanceof Node) {
                continue;
            }

            if (count($property->props) !== 1) {
                continue;
            }

            if (! $classReflection instanceof ClassReflection) {
                $classReflection = $this->reflectionResolver->resolveClassReflection($node);
            }

            // ClassReflection not detected, early skip
            if (! $classReflection instanceof ClassReflection) {
                return null;
            }

            $type = $this->allAssignNodePropertyTypeInferer->inferProperty(
                $property,
                $classReflection,
                $this->file
            );

            if (! $type instanceof Type) {
                continue;
            }

            $propertyType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PROPERTY);
            if (! $propertyType instanceof Node) {
                continue;
            }

            if (! $this->isObjectType($propertyType, new ObjectType(self::MOCK_OBJECT_CLASS))) {
                continue;
            }

            $propertyName = (string) $this->getName($property);
            if (! $this->constructorAssignDetector->isPropertyAssigned($node, $propertyName)) {
                if (! $propertyType instanceof NullableType) {
                    continue;
                }

                $property->props[0]->default = $this->nodeFactory->createNull();
            }

            $property->type = $propertyType;
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_PROPERTIES;
    }
}
