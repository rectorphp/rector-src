<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeManipulator\PropertyManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Privatization\NodeFactory\ClassConstantFactory;
use Rector\Privatization\NodeReplacer\PropertyFetchWithConstFetchReplacer;
use Symplify\PHPStanRules\Reflection\PropertyAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector\ChangeReadOnlyPropertyWithDefaultValueToConstantRectorTest
 */
final class ChangeReadOnlyPropertyWithDefaultValueToConstantRector extends AbstractRector
{
    public function __construct(
        private readonly PropertyManipulator $propertyManipulator,
        private readonly ClassConstantFactory $classConstantFactory,
        private readonly PropertyFetchWithConstFetchReplacer $propertyFetchWithConstFetchReplacer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change property with read only status with default value to constant',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var string[]
     */
    private $magicMethods = [
        '__toString',
        '__wakeup',
    ];

    public function run()
    {
        foreach ($this->magicMethods as $magicMethod) {
            echo $magicMethod;
        }
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var string[]
     */
    private const MAGIC_METHODS = [
        '__toString',
        '__wakeup',
    ];

    public function run()
    {
        foreach (self::MAGIC_METHODS as $magicMethod) {
            echo $magicMethod;
        }
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var PropertyProperty $onlyProperty */
        $onlyProperty = $node->props[0];

        // we need default value
        if ($onlyProperty->default === null) {
            return null;
        }

        if (! $node->isPrivate()) {
            return null;
        }

        // is property read only?
        if ($this->propertyManipulator->isPropertyChangeable($node)) {
            return null;
        }

        /** @var Class_ $classLike */
        $classLike = $this->betterNodeFinder->findParentType($node, Class_::class);
        $this->propertyFetchWithConstFetchReplacer->replace($classLike, $node);

        return $this->classConstantFactory->createFromProperty($node);
    }

    private function shouldSkip(Property $property): bool
    {
        if (count($property->props) !== 1) {
            return true;
        }

        $classLike = $this->betterNodeFinder->findParentType($property, Class_::class);
        if (! $classLike instanceof Class_) {
            return true;
        }

        if ($property->attrGroups !== []) {
            return true;
        }

        if ($this->isObjectType($classLike, new ObjectType('PHP_CodeSniffer\Sniffs\Sniff'))) {
            return true;
        }

        return false;
    }
}
