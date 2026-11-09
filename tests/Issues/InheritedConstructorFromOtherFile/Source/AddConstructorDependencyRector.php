<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InheritedConstructorFromOtherFile\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\MethodName;

final class AddConstructorDependencyRector extends AbstractRector
{
    public function __construct(
        private readonly ClassDependencyManipulator $classDependencyManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
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
    public function refactor(Node $node): ?Class_
    {
        if ($node->getMethod(MethodName::CONSTRUCT) !== null) {
            return null;
        }

        $this->classDependencyManipulator->addConstructorDependency(
            $node,
            new PropertyMetadata('someService', new ObjectType(SomeService::class))
        );

        return $node;
    }
}
