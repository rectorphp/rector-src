<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeVisitorAbstract;
use Rector\PhpParser\Node\CustomNode\StmtsAwareNode;

/**
 * Inspired by https://github.com/phpstan/phpstan-src/pull/3328
 */
final class StmtsAwareWrappingNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        if ($node instanceof If_) {
            return new StmtsAwareNode($node);
        }

        return null;
    }
}
