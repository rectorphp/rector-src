<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeManipulator;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeNameResolver\NodeNameResolver;

final class ControllerClassMethodManipulator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function isControllerClassMethodWithBehaviorAnnotation(Class_ $class, ClassMethod $classMethod): bool
    {
        if (! $this->isControllerClassMethod($class, $classMethod)) {
            return false;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        return $phpDocInfo->hasByType(GenericTagValueNode::class);
    }

    private function isControllerClassMethod(Class_ $class, ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPublic()) {
            return false;
        }

        return $this->hasParentClassController($class);
    }

    private function hasParentClassController(Class_ $class): bool
    {
        if (! $class->extends instanceof Name) {
            return false;
        }

        return $this->nodeNameResolver->endsWith($class->extends, ['Controller', 'Presenter']);
    }
}
