<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\TrustedClassMethodPropertyTypeInferer;
use Rector\ValueObject\MethodName;
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
        private readonly TrustedClassMethodPropertyTypeInferer $trustedClassMethodPropertyTypeInferer
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

        $hasChanged = false;
        foreach ($node->getProperties() as $property) {
            // already typed
            if ($property->type instanceof Node) {
                continue;
            }

            $setUpClassMethod = $node->getMethod(MethodName::SET_UP);
            if (! $setUpClassMethod instanceof ClassMethod) {
                continue;
            }

            $type = $this->trustedClassMethodPropertyTypeInferer->inferProperty(
                $node,
                $property,
                $setUpClassMethod
            );

            if (! $this->isMockObjectType($type)) {
                continue;
            }

            $property->type = new FullyQualified(self::MOCK_OBJECT_CLASS);
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

    private function isMockObjectType(Type $type): bool
    {
        if ($type instanceof ObjectType && $type->isInstanceOf(self::MOCK_OBJECT_CLASS)->yes()) {
            return true;
        }

        return $this->isIntersectionWithMockObjectType($type);
    }

    private function isIntersectionWithMockObjectType(Type $type): bool
    {
        if (! $type instanceof IntersectionType) {
            return false;
        }

        if (count($type->getTypes()) !== 2) {
            return false;
        }

        return in_array(self::MOCK_OBJECT_CLASS, $type->getObjectClassNames());
    }
}
