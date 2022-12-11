<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Rector\Privatization\Guard\ParentPropertyLookupGuard;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\PrivatizeFinalClassPropertyRectorTest
 */
final class PrivatizeFinalClassPropertyRector extends AbstractRector
{
    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ParentPropertyLookupGuard $parentPropertyLookupGuard,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change property to private if possible', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    protected $value;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private $value;
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
        if (! $node->isFinal()) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->getProperties() as $property) {
            if (! $property->isProtected()) {
                continue;
            }

            if (! $this->parentPropertyLookupGuard->isLegal($property)) {
                continue;
            }

            $this->visibilityManipulator->makePrivate($property);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
