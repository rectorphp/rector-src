<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use ReflectionMethod;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://3v4l.org/RFYmn
 *
 * @see \Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\MakeInheritedMethodVisibilitySameAsParentRectorTest
 */
final class MakeInheritedMethodVisibilitySameAsParentRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly PhpVersionProvider $phpVersionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Make method visibility same as parent one', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class ChildClass extends ParentClass
{
    public function run()
    {
    }
}

class ParentClass
{
    protected function run()
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class ChildClass extends ParentClass
{
    protected function run()
    {
    }
}

class ParentClass
{
    protected function run()
    {
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
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        $hasChanged = false;

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($classReflection->getParents() === []) {
            return null;
        }

        foreach ($node->getMethods() as $classMethod) {
            if ($classMethod->isMagic()) {
                continue;
            }

            /** @var string $methodName */
            $methodName = $this->getName($classMethod->name);

            foreach ($classReflection->getParents() as $parentClassReflection) {
                $nativeClassReflection = $parentClassReflection->getNativeReflection();

                // the class reflection above takes also @method annotations into an account
                if (! $nativeClassReflection->hasMethod($methodName)) {
                    continue;
                }

                $parentReflectionMethod = $nativeClassReflection->getMethod($methodName);
                if ($this->isClassMethodCompatibleWithParentReflectionMethod($classMethod, $parentReflectionMethod)) {
                    continue;
                }

                if ($this->isConstructorWithStaticFactory($node, $methodName)) {
                    continue;
                }

                $this->changeClassMethodVisibilityBasedOnReflectionMethod($classMethod, $parentReflectionMethod);
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function isClassMethodCompatibleWithParentReflectionMethod(
        ClassMethod $classMethod,
        ReflectionMethod $reflectionMethod
    ): bool {
        if ($reflectionMethod->isPublic() && $classMethod->isPublic()) {
            return true;
        }

        if ($reflectionMethod->isProtected() && $classMethod->isProtected()) {
            return true;
        }

        return $reflectionMethod->isPrivate() && $classMethod->isPrivate();
    }

    /**
     * Parent constructor visibility override is allowed only since PHP 7.2+
     * @see https://3v4l.org/RFYmn
     */
    private function isConstructorWithStaticFactory(Class_ $class, string $methodName): bool
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::PARENT_VISIBILITY_OVERRIDE)) {
            return false;
        }

        if ($methodName !== MethodName::CONSTRUCT) {
            return false;
        }

        foreach ($class->getMethods() as $iteratedClassMethod) {
            if (! $iteratedClassMethod->isPublic()) {
                continue;
            }

            if (! $iteratedClassMethod->isStatic()) {
                continue;
            }

            $isStaticSelfFactory = $this->isStaticNamedConstructor($iteratedClassMethod);

            if (! $isStaticSelfFactory) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function changeClassMethodVisibilityBasedOnReflectionMethod(
        ClassMethod $classMethod,
        ReflectionMethod $reflectionMethod
    ): void {
        if ($reflectionMethod->isPublic()) {
            $this->visibilityManipulator->makePublic($classMethod);
            return;
        }

        if ($reflectionMethod->isProtected()) {
            $this->visibilityManipulator->makeProtected($classMethod);
            return;
        }

        if ($reflectionMethod->isPrivate()) {
            $this->visibilityManipulator->makePrivate($classMethod);
        }
    }

    /**
     * Looks for:
     * public static someMethod() { return new self(); }
     * or
     * public static someMethod() { return new static(); }
     */
    private function isStaticNamedConstructor(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPublic()) {
            return false;
        }

        if (! $classMethod->isStatic()) {
            return false;
        }

        return (bool) $this->betterNodeFinder->findFirst($classMethod, function (Node $node): bool {
            if (! $node instanceof Return_) {
                return false;
            }

            if (! $node->expr instanceof New_) {
                return false;
            }

            return $this->isNames(
                $node->expr->class,
                [ObjectReference::SELF()->getValue(), ObjectReference::STATIC()->getValue()]
            );
        });
    }
}
