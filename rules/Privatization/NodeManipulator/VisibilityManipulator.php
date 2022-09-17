<?php

declare(strict_types=1);

namespace Rector\Privatization\NodeManipulator;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\Core\ValueObject\Visibility;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Privatization\NodeManipulator\VisibilityManipulatorTest
 */
final class VisibilityManipulator
{
    public function hasVisibility(Class_ | ClassMethod | Property | ClassConst | Param $node, int $visibility): bool
    {
        return (bool) ($node->flags & $visibility);
    }

    /**
     * @api
     */
    public function makeStatic(ClassMethod | Property | ClassConst $node): void
    {
        $this->addVisibilityFlag($node, Visibility::STATIC);
    }

    /**
     * @api
     */
    public function makeNonStatic(ClassMethod | Property $node): void
    {
        if (! $node->isStatic()) {
            return;
        }

        $node->flags -= Class_::MODIFIER_STATIC;
    }

    public function makeAbstract(ClassMethod | Class_ $node): void
    {
        $this->addVisibilityFlag($node, Visibility::ABSTRACT);
    }

    /**
     * @api
     */
    public function makeNonAbstract(ClassMethod | Class_ $node): void
    {
        if (! $node->isAbstract()) {
            return;
        }

        $node->flags -= Class_::MODIFIER_ABSTRACT;
    }

    public function makeFinal(Class_ | ClassMethod | ClassConst $node): void
    {
        $this->addVisibilityFlag($node, Visibility::FINAL);
    }

    /**
     * @api
     */
    public function makeNonFinal(Class_ | ClassMethod $node): void
    {
        if (! $node->isFinal()) {
            return;
        }

        $node->flags -= Class_::MODIFIER_FINAL;
    }

    /**
     * This way "abstract", "static", "final" are kept
     */
    public function removeVisibility(ClassMethod | Property | ClassConst $node): void
    {
        // no modifier
        if ($node->flags === 0) {
            return;
        }

        if ($node->isPublic()) {
            $node->flags |= Class_::MODIFIER_PUBLIC;
            $node->flags -= Class_::MODIFIER_PUBLIC;
        }

        if ($node->isProtected()) {
            $node->flags -= Class_::MODIFIER_PROTECTED;
        }

        if ($node->isPrivate()) {
            $node->flags -= Class_::MODIFIER_PRIVATE;
        }
    }

    public function changeNodeVisibility(ClassMethod | Property | ClassConst $node, int $visibility): void
    {
        Assert::oneOf($visibility, [
            Visibility::PUBLIC,
            Visibility::PROTECTED,
            Visibility::PRIVATE,
            Visibility::STATIC,
            Visibility::ABSTRACT,
            Visibility::FINAL,
        ]);

        $this->replaceVisibilityFlag($node, $visibility);
    }

    public function makePublic(ClassMethod | Property | ClassConst $node): void
    {
        $this->replaceVisibilityFlag($node, Visibility::PUBLIC);
    }

    /**
     * @api
     */
    public function makeProtected(ClassMethod | Property | ClassConst $node): void
    {
        $this->replaceVisibilityFlag($node, Visibility::PROTECTED);
    }

    public function makePrivate(ClassMethod | Property | ClassConst $node): void
    {
        $this->replaceVisibilityFlag($node, Visibility::PRIVATE);
    }

    public function removeFinal(Class_ | ClassConst $node): void
    {
        $node->flags -= Class_::MODIFIER_FINAL;
    }

    public function removeAbstract(ClassMethod $classMethod): void
    {
        $classMethod->flags -= Class_::MODIFIER_ABSTRACT;
    }

    public function makeReadonly(Class_ | Property | Param $node): void
    {
        $this->addVisibilityFlag($node, Visibility::READONLY);
    }

    public function isReadonly(Class_ | Property | Param $node): bool
    {
        return $this->hasVisibility($node, Visibility::READONLY);
    }

    public function removeReadonly(Class_ | Property | Param $node): void
    {
        $this->removeVisibilityFlag($node, Visibility::READONLY);
    }

    /**
     * @api
     */
    private function addVisibilityFlag(
        Class_ | ClassMethod | Property | ClassConst | Param $node,
        int $visibility
    ): void {
        $node->flags |= $visibility;
    }

    private function removeVisibilityFlag(
        Class_ | ClassMethod | Property | ClassConst | Param $node,
        int $visibility
    ): void {
        $node->flags &= ~$visibility;
    }

    private function replaceVisibilityFlag(ClassMethod | Property | ClassConst $node, int $visibility): void
    {
        $isStatic = $node instanceof ClassMethod && $node->isStatic();
        if ($isStatic) {
            $this->makeNonStatic($node);
        }

        if ($visibility !== Visibility::STATIC && $visibility !== Visibility::ABSTRACT && $visibility !== Visibility::FINAL) {
            $this->removeVisibility($node);
        }

        $this->addVisibilityFlag($node, $visibility);

        if ($isStatic) {
            $this->makeStatic($node);
        }
    }
}
