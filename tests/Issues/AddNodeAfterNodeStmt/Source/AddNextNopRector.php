<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Nop;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class AddNextNopRector extends AbstractRector
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
            Nop::class,
        ];
    }

    public function refactor(Node $node)
    {
        $this->nodesToAddCollector->addNodeAfterNode(
            new Echo_([new String_("this is new stmt after Nop")]),
            $node
        );

        return $node;
    }
}
