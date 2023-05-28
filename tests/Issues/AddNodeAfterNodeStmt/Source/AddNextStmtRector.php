<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddNextStmtRector extends AbstractRector
{
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
        return [
            $node,
            new Echo_([new String_("this is new stmt after if")]),
        ];
    }
}
