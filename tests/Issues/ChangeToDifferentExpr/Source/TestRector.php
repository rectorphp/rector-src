<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ChangeToDifferentExpr\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class TestRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('change to different Expr', []);
    }

    public function getNodeTypes(): array
    {
        return [
            String_::class,
        ];
    }

    public function refactor(Node $node)
    {
        return new Assign(new Variable('test'), new Variable('foo'));
    }
}
