<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\DoctrineEntityAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector\FinalizeClassesWithoutChildrenRectorTest
 */
final class FinalizeClassesWithoutChildrenRector extends AbstractRector
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly DoctrineEntityAnalyzer $doctrineEntityAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Finalize every class that has no children', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class FirstClass extends SecondClass
{
}

class SecondClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class FirstClass extends SecondClass
{
}

class SecondClass
{
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
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        if ($this->doctrineEntityAnalyzer->hasClassAnnotation($node)) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->doctrineEntityAnalyzer->hasClassReflectionAttribute($classReflection)) {
            return null;
        }

        $childrenClassReflections = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);
        if ($childrenClassReflections !== []) {
            return null;
        }

        if ($node->attrGroups !== []) {
            // improve reprint with correct newline
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }

        $this->visibilityManipulator->makeFinal($node);

        return $node;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isFinal()) {
            return true;
        }

        if ($class->isAbstract()) {
            return true;
        }

        return $this->classAnalyzer->isAnonymousClass($class);
    }
}
