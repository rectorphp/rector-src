<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InsertLastAfterInlineHTML\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class InsertAfterInlineHTMLRector extends AbstractRector
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

        $echo = new Echo_([new String_('this is new stmt after InlineHTML')]);

        $node->stmts = array_merge(
            $node->stmts,
            [$echo, $echo],
        );

        $this->justAdded[$this->file->getFilePath()] = true;

        return $node;
    }
}
