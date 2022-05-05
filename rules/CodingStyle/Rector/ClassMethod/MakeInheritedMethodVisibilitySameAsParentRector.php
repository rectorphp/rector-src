<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
<<<<<<< HEAD
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
=======
<<<<<<< HEAD
use Rector\Core\Rector\AbstractScopeAwareRector;
=======
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
>>>>>>> 7f16c82a55... make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node
>>>>>>> make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use ReflectionMethod;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://3v4l.org/RFYmn
 *
 * @see \Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\MakeInheritedMethodVisibilitySameAsParentRectorTest
 */
final class MakeInheritedMethodVisibilitySameAsParentRector extends AbstractRector
{
    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ReflectionResolver $reflectionResolver
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
    public function refactor(Node $node): ?Node
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $parentClassReflections = $classReflection->getParents();

        if ($parentClassReflections === []) {
            return null;
        }
<<<<<<< HEAD

<<<<<<< HEAD
=======
        $parentClassReflections = $classReflection->getParents();
=======
>>>>>>> 7f16c82a55... make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node

<<<<<<< HEAD
        $hasChanged = false;
=======
>>>>>>> make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node
>>>>>>> make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node
        foreach ($node->getMethods() as $classMethod) {
            if ($classMethod->isMagic()) {
                continue;
            }

            /** @var string $methodName */
            $methodName = $this->getName($classMethod->name);

<<<<<<< HEAD
            foreach ($parentClassReflections as $parentClassReflection) {
                $nativeClassReflection = $parentClassReflection->getNativeReflection();

                // the class reflection above takes also @method annotations into an account
                if (! $nativeClassReflection->hasMethod($methodName)) {
                    continue;
                }

                $parentReflectionMethod = $nativeClassReflection->getMethod($methodName);
                if ($this->isClassMethodCompatibleWithParentReflectionMethod($classMethod, $parentReflectionMethod)) {
=======
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
>>>>>>> 7f16c82a55... make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node
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

<<<<<<< HEAD
=======
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

>>>>>>> 7f16c82a55... make MakeInheritedMethodVisibilitySameAsParentRector work with scope and Class_ node
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
}
