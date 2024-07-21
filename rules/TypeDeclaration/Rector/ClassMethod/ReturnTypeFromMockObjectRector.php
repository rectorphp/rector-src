<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector\ReturnTypeFromMockObjectRectorTest
 */
final class ReturnTypeFromMockObjectRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const TESTCASE_CLASS = 'PHPUnit\Framework\TestCase';

    /**
     * @var string
     */
    private const MOCK_OBJECT_CLASS = 'PHPUnit\Framework\MockObject\MockObject';

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add known property and return MockObject types', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeTest extends TestCase
{
    public function test()
    {
        $someMock = $this->createMock(SomeClass::class);
        return $someMock;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeTest extends TestCase
{
    public function test(): \PHPUnit\Framework\MockObject\MockObject
    {
        $someMock = $this->createMock(SomeClass::class);
        return $someMock;
    }
}
CODE_SAMPLE
            ),

        ]);
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        // type is already known
        if ($node->returnType instanceof Node) {
            return null;
        }

        if (! $this->isInsideTestCaseClass($scope)) {
            return null;
        }

        if ($this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod($node, $scope)) {
            return null;
        }

        // we need exactly 1 return
        $returns = $this->betterNodeFinder->findReturnsScoped($node);
        if (count($returns) !== 1) {
            return null;
        }

        $soleReturn = $returns[0];
        if (! $soleReturn->expr instanceof Expr) {
            return null;
        }

        $returnType = $this->getType($soleReturn->expr);
        if (! $this->isMockObjectType($returnType)) {
            return null;
        }

        $node->returnType = new FullyQualified(self::MOCK_OBJECT_CLASS);

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
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

    private function isMockObjectType(Type $returnType): bool
    {
        if ($returnType instanceof ObjectType && $returnType->isInstanceOf(self::MOCK_OBJECT_CLASS)->yes()) {
            return true;
        }

        return $this->isIntersectionWithMockObjectType($returnType);
    }

    private function isInsideTestCaseClass(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        // is phpunit test case?
        return $classReflection->isSubclassOf(self::TESTCASE_CLASS);
    }
}
