<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @implements NodeNameResolverInterface<Name>
 */
final class NameNameResolver implements NodeNameResolverInterface
{
    public function __construct(
        private readonly FuncCallNameResolver $funcCallNameResolver
    ) {
    }

    public function getNode(): string
    {
        return Name::class;
    }

    /**
     * @param Name $node
     */
    public function resolve(Node $node, ?Scope $scope): ?string
    {
        // possible function parent
        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof FuncCall) {
            return $this->funcCallNameResolver->resolve($parentNode, $scope);
        }

        return $node->toString();
    }
}
