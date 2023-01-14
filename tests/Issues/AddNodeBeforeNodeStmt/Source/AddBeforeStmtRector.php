<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AddNodeBeforeNodeStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class AddBeforeStmtRector extends AbstractRector
{
    public function __construct(private readonly NodesToAddCollector $nodesToAddCollector)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('uff', []);
    }

    public function getNodeTypes(): array
    {
        return [
            If_::class,
        ];
    }

    public function refactor(Node $node)
    {
        $this->nodesToAddCollector->addNodeBeforeNode(
            new Echo_([new String_("this is new stmt before if")]),
            $node
        );
        return $node;
    }
}
