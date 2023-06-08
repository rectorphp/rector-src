<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\Collector\PropertyToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds new private properties to class + to constructor
 */
final class PropertyAddingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly ClassDependencyManipulator $classDependencyManipulator,
        private readonly PropertyToAddCollector $propertyToAddCollector,
        private readonly ClassAnalyzer $classAnalyzer
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        if ($this->classAnalyzer->isAnonymousClass($node)) {
            return null;
        }

        $this->addProperties($node);

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add dependency properties',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return $this->value;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    private $value;
    public function run()
    {
        return $this->value;
    }
}
CODE_SAMPLE
                ), ]
        );
    }

    private function addProperties(Class_ $class): void
    {
        $propertiesMetadatas = $this->propertyToAddCollector->getPropertiesByClass($class);

        foreach ($propertiesMetadatas as $propertyMetadata) {
            $this->classDependencyManipulator->addConstructorDependency($class, $propertyMetadata);
        }
    }
}
