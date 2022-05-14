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
    private const NAME_NODES = [
        Name::class,
        Namespace_::class,
        FileWithoutNamespace::class,
        Identifier::class
    ];

    public function hasScope(Node $node): bool
    {
        foreach (self::NAME_NODES as $nameNode) {
            if ($node instanceof $nameNode) {
                return false;
            }
        }

        return true;
    }
}