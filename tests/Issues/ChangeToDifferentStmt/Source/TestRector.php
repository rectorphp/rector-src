<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ChangeToDifferentStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class TestRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('change to different Stmt', []);
    }

    public function getNodeTypes(): array
    {
        return [
            If_::class,
        ];
    }

    public function refactor(Node $node)
    {
        return new Echo_([new String_('test')]);
    }
}
