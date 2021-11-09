<?php

declare(strict_types=1);

namespace Rector\Php70\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\ObjectType;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeCollector\StaticAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://3v4l.org/rkiSC
 * @see \Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\ThisCallOnStaticMethodToStaticCallRectorTest
 */
final class ThisCallOnStaticMethodToStaticCallRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private StaticAnalyzer $staticAnalyzer,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::STATIC_CALL_ON_NON_STATIC;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes $this->call() to static method to static call',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public static function run()
    {
        $this->eat();
    }

    public static function eat()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public static function run()
    {
        static::eat();
    }

    public static function eat()
    {
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
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof Variable) {
            return null;
        }

        if (! $this->nodeNameResolver->isName($node->var, 'this')) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        // skip PHPUnit calls, as they accept both self:: and $this-> formats
        if ($this->isObjectType($node->var, new ObjectType('PHPUnit\Framework\TestCase'))) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($classLike);

        $isStaticMethod = $this->staticAnalyzer->isStaticMethod($methodName, $className);
        if (! $isStaticMethod) {
            return null;
        }

        $objectReference = $this->resolveClassSelf($node);
        return $this->nodeFactory->createStaticCall($objectReference, $methodName, $node->args);
    }

    private function resolveClassSelf(MethodCall $methodCall): ObjectReference
    {
        $classLike = $this->betterNodeFinder->findParentType($methodCall, Class_::class);
        if (! $classLike instanceof Class_) {
            return ObjectReference::STATIC();
        }

        if ($classLike->isFinal()) {
            return ObjectReference::SELF();
        }

        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromMethodCall($methodCall);
        if (! $methodReflection instanceof PhpMethodReflection) {
            return ObjectReference::STATIC();
        }

        if (! $methodReflection->isPrivate()) {
            return ObjectReference::STATIC();
        }

        return ObjectReference::SELF();
    }
}
