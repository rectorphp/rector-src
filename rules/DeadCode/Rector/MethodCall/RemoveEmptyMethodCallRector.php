<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
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
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ThisType;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\NodeAnalyzer\CallAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector\RemoveEmptyMethodCallRectorTest
 */
final class RemoveEmptyMethodCallRector extends AbstractScopeAwareRector
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
        return [Expression::class, Assign::class, If_::class];
    }

    /**
     * @param Expression|Assign|If_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope)
    {
        if ($node instanceof Expression) {
            if (! $node->expr instanceof MethodCall) {
                return null;
            }

            $methodCall = $node->expr;
            if ($this->shouldSkipMethodCall($methodCall)) {
                return null;
            }

            $type = $scope->getType($methodCall->var);
            if (! $type instanceof TypeWithClassName) {
                return null;
            }

            $classLike = $this->resolveClassLike($methodCall);
            if (! $classLike instanceof ClassLike) {
                return null;
            }

            /** @var Class_|Trait_|Interface_|Enum_ $classLike */
            if ($this->shouldSkipClassMethod($classLike, $methodCall, $type)) {
                return null;
            }

            // if->cond cannot removed, it has to be replaced with false, see https://3v4l.org/U9S9i
            $parentNode = $methodCall->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof If_ && $parentNode->cond === $methodCall) {
                return $this->nodeFactory->createFalse();
            }

            if ($parentNode instanceof Assign) {
                return $this->nodeFactory->createFalse();
            }

            //            if ($parentNode instanceof ArrowFunction && $this->nodeComparator->areNodesEqual($parentNode->expr, $methodCall)) {
            //                return $this->processArrowFunction($parentNode, $methodCall);
            //            }

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

    private function shouldSkipMethodCall(MethodCall $methodCall): bool
    {
        if ($this->callAnalyzer->isObjectCall($methodCall->var)) {
            return true;
        }

        $parentArg = $this->betterNodeFinder->findParentType($methodCall, Arg::class);
        return $parentArg instanceof Arg;
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
}
