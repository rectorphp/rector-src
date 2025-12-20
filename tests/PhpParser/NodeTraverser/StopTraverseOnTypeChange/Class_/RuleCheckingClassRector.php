<?php

namespace Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RuleCheckingClassRector extends AbstractRector
{

    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     * @return Class_
     */
    public function refactor(Node $node)
    {
        Assert::isInstanceOf($node, Class_::class);

        return $node;
    }
}
