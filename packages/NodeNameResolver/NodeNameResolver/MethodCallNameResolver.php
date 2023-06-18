<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;

/**
 * @implements NodeNameResolverInterface<MethodCall>
 */
final class MethodCallNameResolver implements NodeNameResolverInterface
{
    /**
     * @var string
     */
    public const IS_EXPR_NAME = 'is_expr_name';

    public function getNode(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     */
    public function resolve(Node $node, ?Scope $scope): ?string
    {
        if ($node->name instanceof Expr) {
            $node->name->setAttribute(self::IS_EXPR_NAME, true);
            return null;
        }

        return (string) $node->name;
    }
}
