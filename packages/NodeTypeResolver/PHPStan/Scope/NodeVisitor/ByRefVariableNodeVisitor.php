<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class ByRefVariableNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    /**
     * @param string[] $byRefVariableNames
     * @return string[]
     */
    private function resolveClosureUseIsByRefAttribute(FunctionLike $node, array $byRefVariableNames): array
    {
        if (! $node instanceof Closure) {
            return $byRefVariableNames;
        }

        foreach ($node->uses as $closureUse) {
            if ($closureUse->byRef && is_string($closureUse->var->name)) {
                $closureUse->var->setAttribute(AttributeKey::IS_BYREF_VAR, true);
                $byRefVariableNames[] = $closureUse->var->name;
            }
        }

        return $byRefVariableNames;
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof FunctionLike) {
            if ($node instanceof AssignRef) {
                $node->expr->setAttribute(AttributeKey::IS_BYREF_VAR, true);
            }

            return null;
        }

        $stmts = (array) $node->getStmts();

        $byRefVariableNames = $this->resolveClosureUseIsByRefAttribute($node, []);

        foreach ($node->getParams() as $param) {
            if ($param->byRef && $param->var instanceof Variable && is_string($param->var->name)) {
                $param->var->setAttribute(AttributeKey::IS_BYREF_VAR, true);
                $byRefVariableNames[] = $param->var->name;
            }
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $stmts,
            static function (Node $subNode) use ($byRefVariableNames): null|Variable {
                if (! $subNode instanceof Variable) {
                    return null;
                }

                if (! in_array($subNode->name, $byRefVariableNames, true)) {
                    return null;
                }

                $subNode->setAttribute(AttributeKey::IS_BYREF_VAR, true);
                return $subNode;
            }
        );

        return null;
    }
}
