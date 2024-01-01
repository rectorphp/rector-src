<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Class_;

use Nette\Utils\Arrays;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Reflection\ClassReflection;
use Rector\Collector\ParentClassCollector;
use Rector\Core\Rector\AbstractCollectorRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeAnalyzer\DoctrineEntityAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector\FinalizeClassesWithoutChildrenCollectorRectorTest
 */
final class FinalizeClassesWithoutChildrenCollectorRector extends AbstractCollectorRector
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly DoctrineEntityAnalyzer $doctrineEntityAnalyzer,
    ) {
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

        $parentClassNames = $this->resolveCollectedParentClassNames($this->getCollectedDataNode());

        // the class is being extended in the code, so we should skip it here
        if ($this->nodeNameResolver->isNames($node, $parentClassNames)) {
            return null;
        }

        if ($node->attrGroups !== []) {
            // improve reprint with correct newline
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }

        $this->visibilityManipulator->makeFinal($node);

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('...', []);
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isFinal() || $class->isAbstract()) {
            return true;
        }

        return $this->classAnalyzer->isAnonymousClass($class);
    }

    /**
     * @return string[]
     */
    private function resolveCollectedParentClassNames(CollectedDataNode $collectedDataNode): array
    {
        $parentClassCollectorData = $collectedDataNode->get(ParentClassCollector::class);
        $parentClassNames = Arrays::flatten($parentClassCollectorData);

        return array_unique($parentClassNames);
    }
}
