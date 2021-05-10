<?php

declare(strict_types=1);

namespace Rector\PhpSpecToPHPUnit;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;

final class LetManipulator
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isLetNeededInClass(Class_ $class): bool
    {
        foreach ($class->getMethods() as $classMethod) {
            // new test
            if ($this->nodeNameResolver->isName($classMethod, 'test*')) {
                continue;
            }

            $hasBeConstructedThrough = (bool) $this->betterNodeFinder->find(
                (array) $classMethod->stmts,
                function (Node $node): ?bool {
                    if (! $node instanceof MethodCall) {
                        return null;
                    }

                    return $this->nodeNameResolver->isName($node->name, 'beConstructedThrough');
                }
            );

            if ($hasBeConstructedThrough) {
                continue;
            }

            return true;
        }

        return false;
    }
}
