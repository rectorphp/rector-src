<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InsertFirstBeforeInlineHTML\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class InsertBeforeInlineHTMLRector extends AbstractRector
{
    private array $justAdded = [];

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
        if (isset($this->justAdded[$this->file->getFilePath()])) {
            return null;
        }

        $echo = new Echo_([new String_('this is new stmt before InlineHTML')]);

        $node->stmts = array_merge(
            [$echo],
            $node->stmts
        );

        $this->justAdded[$this->file->getFilePath()] = true;

        return $node;
    }
}
