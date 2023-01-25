<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ReturnArrayNodeOnInlineHTML\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class ArrayOnInlineHTMLRector extends AbstractRector
{
    private bool $justAdded = false;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('uff', []);
    }

    public function getNodeTypes(): array
    {
        return [
            FileWithoutNamespace::class,
        ];
    }

    public function refactor(Node $node)
    {
        if ($this->justAdded) {
            return null;
        }

        $echo = new Echo_([new String_('this is new stmt before InlineHTML')]);

        $node->stmts = array_merge(
            [$echo],
            $node->stmts
        );

        $this->justAdded = true;

        return $node;
    }
}
