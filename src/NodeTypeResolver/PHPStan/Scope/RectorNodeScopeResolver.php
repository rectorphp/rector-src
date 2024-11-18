<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope;

use PhpParser\Node;
use PHPStan\Analyser\MutatingScope;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

/**
 * Handle Scope filling when there is error \PHPStan\Parser\ParserErrorsException
 * from PHPStan NodeScopeResolver
 */
final class RectorNodeScopeResolver
{
    public static function processNodes(array $stmts, MutatingScope $mutatingScope): void
    {
        $nodeTraverser = new SimpleCallableNodeTraverser();

        $nodeTraverser->traverseNodesWithCallable(
            $stmts,
            function (Node $node) use ($mutatingScope) {
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
        );
    }
}