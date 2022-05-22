<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class UseImportsResolver
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @return Use_[]|GroupUse[]
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

        $collectedUses = [];

        foreach ($namespace->stmts as $stmt) {
            if ($stmt instanceof Use_) {
                $collectedUses[] = $stmt;
                continue;
            }

            if ($stmt instanceof GroupUse) {
                foreach ($stmt->uses as $key => $useUse) {
                    $stmt->uses[$key]->name = new Name($stmt->prefix . '\\' . $useUse->name);
                }

                $collectedUses[] = $stmt;
            }
        }

        return $collectedUses;
    }
}
