<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\NeverType;
use Rector\Enum\ClassName;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Class_\ClosureReturnTypeFromAssertInstanceOfRector\ClosureReturnTypeFromAssertInstanceOfRectorTest
 */
final class ClosureReturnTypeFromAssertInstanceOfRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add return type to closures narrowed by assertInstanceOf() inside PHPUnit test case classes',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    public function test()
    {
        $callback = function (object $object) {
            $this->assertInstanceOf(SomeType::class, $object);

            return $object;
        };
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    public function test()
    {
        $callback = function (object $object): SomeType {
            $this->assertInstanceOf(SomeType::class, $object);

            return $object;
        };
    }
}
CODE_SAMPLE
                ),
            ]
        );
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
        $classReflection = $this->fetchClassLikeReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        // is phpunit test case?
        if (! $classReflection->is(ClassName::TEST_CASE_CLASS)) {
            return null;
        }

        $hasChanged = false;

        /** @var Closure[] $closures */
        $closures = $this->betterNodeFinder->findInstancesOf($node, [Closure::class]);
        foreach ($closures as $closure) {
            // type is already set
            if ($closure->returnType instanceof Node) {
                continue;
            }

            // only handle closures narrowed by assertInstanceOf(); plain returns are handled by ClosureReturnTypeRector
            if (! $this->hasAssertInstanceOf($closure)) {
                continue;
            }

            $closureReturnType = $this->returnTypeInferer->inferFunctionLike($closure);

            // handled by other rules
            if ($closureReturnType instanceof NeverType) {
                continue;
            }

            $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($closureReturnType, TypeKind::RETURN);
            if (! $returnTypeNode instanceof Node) {
                continue;
            }

            $closure->returnType = $returnTypeNode;
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }

    private function hasAssertInstanceOf(Closure $closure): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $closure->stmts,
            fn (Node $node): bool => ($node instanceof MethodCall || $node instanceof StaticCall)
                && $this->isName($node->name, 'assertInstanceOf')
        );
    }
}
