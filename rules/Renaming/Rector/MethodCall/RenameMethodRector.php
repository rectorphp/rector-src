<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\MethodCall;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeManipulator\ClassManipulator;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\Reflection\ReflectionResolver;
use Rector\Renaming\Contract\MethodCallRenameInterface;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\MethodCallRenameWithArrayKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\RenameMethodRectorTest
 */
final class RenameMethodRector extends AbstractScopeAwareRector implements ConfigurableRectorInterface
{
    /**
     * @var MethodCallRenameInterface[]
     */
    private array $methodCallRenames = [];

    public function __construct(
        private readonly ClassManipulator $classManipulator,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns method names to new ones.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$someObject = new SomeExampleClass;
$someObject->oldMethod();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$someObject = new SomeExampleClass;
$someObject->newMethod();
CODE_SAMPLE
                ,
                [new MethodCallRename('SomeExampleClass', 'oldMethod', 'newMethod')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, NullsafeMethodCall::class, StaticCall::class, Class_::class, Interface_::class];
    }

    /**
     * @param MethodCall|NullsafeMethodCall|StaticCall|Class_|Interface_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node instanceof Class_ || $node instanceof Interface_) {
            return $this->refactorClass($node, $scope);
        }

        return $this->refactorMethodCallAndStaticCall($node);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, MethodCallRenameInterface::class);

        $this->methodCallRenames = $configuration;
    }

    private function shouldSkipClassMethod(
        MethodCall|NullsafeMethodCall|StaticCall $call,
        MethodCallRenameInterface $methodCallRename
    ): bool {
        $classReflection = $this->reflectionResolver->resolveClassReflectionSourceObject($call);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $targetClass = $methodCallRename->getClass();
        if (! $this->reflectionProvider->hasClass($targetClass)) {
            return false;
        }

        $targetClassReflection = $this->reflectionProvider->getClass($targetClass);
        if ($classReflection->getName() === $targetClassReflection->getName()) {
            return false;
        }

        // different with configured ClassLike source? it is a child, which may has old and new exists
        if (! $classReflection->hasMethod($methodCallRename->getOldMethod())) {
            return false;
        }

        return $classReflection->hasMethod($methodCallRename->getNewMethod());
    }

    private function hasClassNewClassMethod(
        Class_|Interface_ $classOrInterface,
        MethodCallRenameInterface $methodCallRename
    ): bool {
        return (bool) $classOrInterface->getMethod($methodCallRename->getNewMethod());
    }

    private function shouldKeepForParentInterface(
        MethodCallRenameInterface $methodCallRename,
        ?ClassReflection $classReflection
    ): bool {
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        // interface can change current method, as parent contract is still valid
        if (! $classReflection->isInterface()) {
            return false;
        }

        return $this->classManipulator->hasParentMethodOrInterface(
            $methodCallRename->getObjectType(),
            $methodCallRename->getOldMethod(),
            $methodCallRename->getNewMethod()
        );
    }

    private function refactorClass(Class_|Interface_ $classOrInterface, Scope $scope): Class_|Interface_|null
    {
        if (! $scope->isInClass()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();

        $hasChanged = false;

        foreach ($classOrInterface->getMethods() as $classMethod) {
            $methodName = $this->getName($classMethod->name);
            if ($methodName === null) {
                continue;
            }

            foreach ($this->methodCallRenames as $methodCallRename) {
                if ($this->shouldSkipRename(
                    $methodName,
                    $classMethod,
                    $methodCallRename,
                    $classReflection,
                    $classOrInterface
                )) {
                    continue;
                }

                $classMethod->name = new Identifier($methodCallRename->getNewMethod());
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $classOrInterface;
        }

        return null;
    }

    private function shouldSkipRename(
        string $methodName,
        ClassMethod $classMethod,
        MethodCallRenameInterface $methodCallRename,
        ClassReflection $classReflection,
        Class_|Interface_ $classOrInterface
    ): bool {
        if (! $this->nodeNameResolver->isStringName($methodName, $methodCallRename->getOldMethod())) {
            return true;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $classMethod,
            $methodCallRename->getObjectType()
        )) {
            return true;
        }

        if ($this->shouldKeepForParentInterface($methodCallRename, $classReflection)) {
            return true;
        }

        return $this->hasClassNewClassMethod($classOrInterface, $methodCallRename);
    }

    private function refactorMethodCallAndStaticCall(
        StaticCall|MethodCall|NullsafeMethodCall $call
    ): ArrayDimFetch|null|MethodCall|StaticCall|NullsafeMethodCall {
        $callName = $this->getName($call->name);
        if ($callName === null) {
            return null;
        }

        foreach ($this->methodCallRenames as $methodCallRename) {
            if (! $this->nodeNameResolver->isStringName($callName, $methodCallRename->getOldMethod())) {
                continue;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $call,
                $methodCallRename->getObjectType()
            )) {
                continue;
            }

            if ($this->shouldSkipClassMethod($call, $methodCallRename)) {
                continue;
            }

            $call->name = new Identifier($methodCallRename->getNewMethod());

            if ($methodCallRename instanceof MethodCallRenameWithArrayKey) {
                return new ArrayDimFetch($call, BuilderHelpers::normalizeValue($methodCallRename->getArrayKey()));
            }

            return $call;
        }

        return null;
    }
}
