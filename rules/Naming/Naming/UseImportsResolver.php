<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class UseImportsResolver
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @return Use_[]
     */
    public function resolveForNode(Node $node): array
    {
        $namespace = $this->betterNodeFinder->findParentByTypes(
            $node,
            [Namespace_::class, FileWithoutNamespace::class]
        );
        if (! $namespace instanceof Node) {
            return [];
        }

        $uses = [];

        foreach ($namespace->stmts as $stmt) {
            if ($stmt instanceof Use_) {
                $uses[] = $stmt;
                continue;
            }

            if ($stmt instanceof GroupUse) {
                $uses[] = new Use_($stmt->uses);
            }
        }

        return $uses;
    }
}
