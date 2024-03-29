<?php

declare(strict_types=1);

namespace Rector\Naming\AssignVariableNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use Rector\Exception\NotImplementedYetException;
use Rector\Naming\Contract\AssignVariableNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;

/**
 * @implements AssignVariableNameResolverInterface<New_>
 */
final readonly class NewAssignVariableNameResolver implements AssignVariableNameResolverInterface
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function match(Node $node): bool
    {
        return $node instanceof New_;
    }

    /**
     * @param New_ $node
     */
    public function resolve(Node $node): string
    {
        $className = $this->nodeNameResolver->getName($node->class);
        if ($className === null) {
            throw new NotImplementedYetException();
        }

        return $this->nodeNameResolver->getShortName($className);
    }
}
