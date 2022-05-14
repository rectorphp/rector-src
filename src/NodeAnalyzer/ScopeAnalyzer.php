<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class ScopeAnalyzer
{
    /**
     * @var array<class-string<Node>>
     */
    private const NAME_NODES = [Name::class, Namespace_::class, FileWithoutNamespace::class, Identifier::class];

    public function hasScope(Node $node): bool
    {
        $nodeClass = $node::class;
        foreach (self::NAME_NODES as $nameNode) {
            if (is_a($nodeClass, $nameNode, true)) {
                return false;
            }
        }

        return true;
    }
}
