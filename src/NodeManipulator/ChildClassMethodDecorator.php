<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;

final class ChildClassMethodDecorator
{
    public function __construct(
        private NodeFactory $nodeFactory,
        private NodeNameResolver $nodeNameResolver,
        private NodeRepository $nodeRepository,
    ) {
    }

    /**
     * @deprecated This behavior depends on the analysed class order, and is not reliable. Thus should be removed,
     * along with rule that build on it. We can cause serious bugs otherwise.
     *
     * Maybe PHPStan is able provide class tree some day and this behavior may be supported.
     */
    public function completeChildConstructors(Class_ $class, ClassMethod $constructorClassMethod): void
    {
        // @todo
        return;

        $className = $this->nodeNameResolver->getName($class);
        if ($className === null) {
            return;
        }

        $childClasses = $this->nodeRepository->findChildrenOfClass($className);

        foreach ($childClasses as $childClass) {
            $childConstructorClassMethod = $childClass->getMethod(MethodName::CONSTRUCT);
            if (! $childConstructorClassMethod instanceof ClassMethod) {
                continue;
            }

            // replicate parent parameters
            $childConstructorClassMethod->params = array_merge(
                $constructorClassMethod->params,
                $childConstructorClassMethod->params
            );

            $parentConstructCallNode = $this->nodeFactory->createParentConstructWithParams(
                $constructorClassMethod->params
            );

            $childConstructorClassMethod->stmts = array_merge(
                [new Expression($parentConstructCallNode)],
                (array) $childConstructorClassMethod->stmts
            );
        }
    }
}
