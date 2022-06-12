<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
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

        return array_filter(
            $namespace->stmts,
            fn (Stmt $stmt): bool => $stmt instanceof Use_ || $stmt instanceof GroupUse
        );
    }

    /**
     * @return Use_[]
     */
    public function resolveBareUsesForNode(Node $node): array
    {
        $namespace = $this->betterNodeFinder->findParentByTypes(
            $node,
            [Namespace_::class, FileWithoutNamespace::class]
        );
        if (! $namespace instanceof Node) {
            return [];
        }

        return array_filter($namespace->stmts, fn (Stmt $stmt): bool => $stmt instanceof Use_);
    }

    public function resolvePrefix(Use_|GroupUse $use): string
    {
        return $use instanceof GroupUse
            ? $use->prefix . '\\'
            : '';
    }

    public function resolveFromName(Name $name): null|Use_|GroupUse
    {
        $className = $name->toString();

        $uses = $this->resolveForNode($name);

        foreach ($uses as $use) {
            $prefix = $this->resolvePrefix($use);

            foreach ($use->uses as $useUse) {
                $useClassName = $prefix . $useUse->name->toString();
                if ($useClassName === $className) {
                    return $use;
                }
            }
        }

        return null;
    }
}
