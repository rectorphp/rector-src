<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\FileWithoutNamespaceCompat\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PhpParser\Node\FileNode;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SubscribedToFileWithoutNamespaceRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adds a function to FileWithoutNamespace nodes', []);
    }

    public function getNodeTypes(): array
    {
        return [FileWithoutNamespace::class];
    }

    /**
     * @param FileWithoutNamespace $node
     */
    public function refactor(Node $node): FileNode
    {
        $function = new Function_('someFunction');
        // required for PHPStan scope resolver refresh
        $function->namespacedName = new Node\Name('someFunction');

        $node->stmts[] = $function;

        return $node;
    }
}
