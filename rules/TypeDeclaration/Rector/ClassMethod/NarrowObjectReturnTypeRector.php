<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Narrows return types from generic object or parent class to specific class.
 *
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\NarrowObjectReturnTypeRectorTest
 */
final class NarrowObjectReturnTypeRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Narrows return type from generic object or parent class to specific class',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class TalkFactory extends AbstractFactory
{
    protected function build(): object
    {
        return new ConferenceTalk();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class TalkFactory extends AbstractFactory
{
    protected function build(): ConferenceTalk
    {
        return new ConferenceTalk();
    }
}
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class TalkFactory
{
    public function createConferenceTalk(): Talk
    {
        return new ConferenceTalk();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class TalkFactory
{
    public function createConferenceTalk(): ConferenceTalk
    {
        return new ConferenceTalk();
    }
}
CODE_SAMPLE
                ),
            ],
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof ClassMethod) {
            return null;
        }

        $returnType = $node->returnType;

        if (! $returnType instanceof Identifier && ! $returnType instanceof FullyQualified) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if ($classReflection instanceof ClassReflection && $classReflection->isAbstract()) {
            return null;
        }

        if ($this->hasParentMethodWithNonObjectReturn($node)) {
            return null;
        }

        $actualReturnClass = $this->getActualReturnClass($node);

        if ($actualReturnClass === null) {
            return null;
        }

        $declaredType = $returnType->toString();

        if ($declaredType === $actualReturnClass) {
            return null;
        }

        if ($this->isDeclaredTypeFinal($declaredType)) {
            return null;
        }

        if ($this->isActualTypeAnonymous($actualReturnClass)) {
            return null;
        }

        if (! $this->isNarrowingValid($declaredType, $actualReturnClass)) {
            return null;
        }

        $node->returnType = new FullyQualified($actualReturnClass);

        return $node;
    }

    private function isDeclaredTypeFinal(string $declaredType): bool
    {
        if ($declaredType === 'object') {
            return false;
        }

        $declaredObjectType = new ObjectType($declaredType);
        $classReflection = $declaredObjectType->getClassReflection();

        if ($classReflection === null) {
            return false;
        }

        return $classReflection->isFinal();
    }

    private function isActualTypeAnonymous(string $actualType): bool
    {
        $actualObjectType = new ObjectType($actualType);
        $classReflection = $actualObjectType->getClassReflection();

        if ($classReflection === null) {
            return false;
        }

        return $classReflection->isAnonymous();
    }

    private function isNarrowingValid(string $declaredType, string $actualType): bool
    {
        if ($declaredType === 'object') {
            return true;
        }

        $actualObjectType = new ObjectType($actualType);
        $declaredObjectType = new ObjectType($declaredType);

        return $declaredObjectType->isSuperTypeOf($actualObjectType)
            ->yes();
    }

    private function hasParentMethodWithNonObjectReturn(ClassMethod $classMethod): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $ancestors = array_filter(
            $classReflection->getAncestors(),
            fn (ClassReflection $ancestorClassReflection): bool =>
            $classReflection->getName() !== $ancestorClassReflection->getName()
        );

        $methodName = $this->getName($classMethod);
        foreach ($ancestors as $ancestor) {
            if ($ancestor->getFileName() === null) {
                continue;
            }

            if (! $ancestor->hasNativeMethod($methodName)) {
                continue;
            }

            $parentMethod = $ancestor->getNativeMethod($methodName);
            $parentReturnType = $parentMethod->getVariants()[0]
                ->getReturnType();

            if ($parentReturnType->isObject()->yes() && $parentReturnType->getObjectClassNames() === []) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function getActualReturnClass(ClassMethod $node): ?string
    {
        $returnStatements = $this->betterNodeFinder->findInstanceOf($node, Return_::class);

        if ($returnStatements === []) {
            return null;
        }

        $returnedClass = null;

        foreach ($returnStatements as $returnStatement) {
            if ($returnStatement->expr === null) {
                return null;
            }

            $returnType = $this->nodeTypeResolver->getNativeType($returnStatement->expr);

            if (! $returnType->isObject()->yes()) {
                return null;
            }

            $classNames = $returnType->getObjectClassNames();

            if (count($classNames) !== 1) {
                return null;
            }

            $className = $classNames[0];

            if ($returnedClass === null) {
                $returnedClass = $className;
            } elseif ($returnedClass !== $className) {
                return null;
            }
        }

        return $returnedClass;
    }
}
