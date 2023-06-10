<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ThisType;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\NodeAnalyzer\CallAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector\RemoveEmptyMethodCallRectorTest
 */
final class RemoveEmptyMethodCallRector extends AbstractRector
{
    public function __construct(
        private readonly AstResolver $reflectionAstResolver,
        private readonly CallAnalyzer $callAnalyzer,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove empty method call', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function callThis()
    {
    }
}

$some = new SomeClass();
$some->callThis();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function callThis()
    {
    }
}

$some = new SomeClass();
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [If_::class, Expression::class];
    }

    /**
     * @param If_|Expression $node
     */
    public function refactor(Node $node)
    {
        if ($node instanceof If_) {
            return $this->refactorIf($node);
        }

        if ($node->expr instanceof Assign) {
            $assign = $node->expr;
            if (! $assign->expr instanceof MethodCall) {
                return null;
            }

            if (! $this->shouldRemoveMethodCall($assign->expr)) {
                return null;
            }

            $assign->expr = $this->nodeFactory->createFalse();
            return $node;
        }

        if ($node->expr instanceof MethodCall) {
            $methodCall = $node->expr;
            if (! $this->shouldRemoveMethodCall($methodCall)) {
                return null;
            }

            return NodeTraverser::REMOVE_NODE;
        }

        return null;
    }

    private function resolveClassLike(MethodCall $methodCall): ?ClassLike
    {
        $classReflection = $this->reflectionResolver->resolveClassReflectionSourceObject($methodCall);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        return $this->reflectionAstResolver->resolveClassFromName($classReflection->getName());
    }

    private function shouldSkipClassMethod(
        Class_ | Trait_ | Interface_ | Enum_ $classLike,
        MethodCall $methodCall,
        TypeWithClassName $typeWithClassName
    ): bool {
        if (! $classLike instanceof Class_) {
            return true;
        }

        $methodName = $this->getName($methodCall->name);
        if ($methodName === null) {
            return true;
        }

        $classMethod = $classLike->getMethod($methodName);
        if (! $classMethod instanceof ClassMethod) {
            return true;
        }

        if ($classMethod->isAbstract()) {
            return true;
        }

        if ((array) $classMethod->stmts !== []) {
            return true;
        }

        $class = $this->betterNodeFinder->findParentType($methodCall, Class_::class);
        if (! $class instanceof Class_) {
            return false;
        }

        if (! $typeWithClassName instanceof ThisType) {
            return false;
        }

        if ($class->isFinal()) {
            return false;
        }

        return ! $classMethod->isPrivate();
    }

    private function shouldRemoveMethodCall(MethodCall $methodCall): bool
    {
        if ($this->callAnalyzer->isObjectCall($methodCall->var)) {
            return false;
        }

        $callerType = $this->getType($methodCall->var);
        if (! $callerType instanceof TypeWithClassName) {
            return false;
        }

        $classLike = $this->resolveClassLike($methodCall);
        if (! $classLike instanceof ClassLike) {
            return false;
        }

        /** @var Class_|Trait_|Interface_|Enum_ $classLike */
        return ! $this->shouldSkipClassMethod($classLike, $methodCall, $callerType);
    }

    /**
     * If->cond cannot removed,
     * it has to be replaced with false, see https://3v4l.org/U9S9i
     */
    private function refactorIf(If_ $if): ?If_
    {
        if (! $if->cond instanceof MethodCall) {
            return null;
        }

        if (! $this->shouldRemoveMethodCall($if->cond)) {
            return null;
        }

        $if->cond = $this->nodeFactory->createFalse();

        return $if;
    }
}
