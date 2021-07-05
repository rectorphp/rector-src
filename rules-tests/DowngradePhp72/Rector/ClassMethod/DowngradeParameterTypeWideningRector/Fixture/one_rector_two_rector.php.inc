<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class OneRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        // TODO: Implement getRuleDefinition() method.
    }

    public function getNodeTypes(): array
    {
        // TODO: Implement getNodeTypes() method.
    }

    /**
     * @param Node\Scalar\String_ $node
     */
    public function refactor(Node $node)
    {
        // TODO: Implement refactor() method.
    }
}


final class TwoRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        // TODO: Implement getRuleDefinition() method.
    }

    public function getNodeTypes(): array
    {
        // TODO: Implement getNodeTypes() method.
    }

    /**
     * @param Node\Stmt\ClassMethod|Node\Expr\MethodCall $node
     */
    public function refactor(Node $node)
    {
        // TODO: Implement refactor() method.
    }
}
