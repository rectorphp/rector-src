<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope;

use PhpParser\Node;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Stmt;
use PHPStan\Node\Expr\AlwaysRememberedExpr;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class RectorVirtualNodeExtractor
{
    /**
     * @param Stmt[] $stmts
     */
    public static function processNodes(array $stmts): void
    {
        $simpleCallableNodeTraverser = new SimpleCallableNodeTraverser();

        $simpleCallableNodeTraverser->traverseNodesWithCallable(
            $stmts,
            function (Node $node): ?Node {
                $hasRememberedExpr = false;

                // handle already AlwaysRememberedExpr
                // @see https://github.com/rectorphp/rector/issues/8815#issuecomment-2503453191
                while ($node instanceof AlwaysRememberedExpr) {
                    $node = $node->getExpr();
                    $hasRememberedExpr = true;
                }

                // handle overlapped origNode is Match_
                // and its subnodes still have AlwaysRememberedExpr
                $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);

                if ($originalNode instanceof Match_) {
                    $subNodeNames = $node->getSubNodeNames();
                    foreach ($subNodeNames as $subNodeName) {
                        while ($originalNode->{$subNodeName} instanceof AlwaysRememberedExpr) {
                            $originalNode->{$subNodeName} = $originalNode->{$subNodeName}->getExpr();
                            $hasRememberedExpr = true;
                        }
                    }
                }

                if (! $hasRememberedExpr) {
                    return null;
                }

                return $node;
            }
        );
    }
}
