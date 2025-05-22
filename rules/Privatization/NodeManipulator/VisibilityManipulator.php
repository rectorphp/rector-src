<?php

declare(strict_types=1);

namespace Rector\Privatization\NodeManipulator;

use PhpParser\Modifiers;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\ValueObject\Visibility;
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
    public function makeStatic(ClassMethod | Property | ClassConst | Param $node): void
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

        $node->flags -= Modifiers::STATIC;
    }

    /**
     * @api
     */
    public function makeNonAbstract(ClassMethod | Class_ $node): void
    {
        if (! $node->isAbstract()) {
            return;
        }

        $node->flags -= Modifiers::ABSTRACT;
    }

    /**
     * @api
     */
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

        $node->flags -= Modifiers::FINAL;
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

    public function makePublic(ClassMethod | Property | ClassConst | Param $node): void
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

    public function makePrivate(ClassMethod | Property | ClassConst | Param $node): void
    {
        $this->replaceVisibilityFlag($node, Visibility::PRIVATE);
    }

    /**
     * @api
     */
    public function removeFinal(Class_ | ClassConst $node): void
    {
        $node->flags -= Modifiers::FINAL;
    }

    public function makeReadonly(Class_ | Property | Param $node): void
    {
        $this->addVisibilityFlag($node, Visibility::READONLY);
    }

    /**
     * @api
     */
    public function isReadonly(Class_ | Property | Param $node): bool
    {
        return $this->hasVisibility($node, Visibility::READONLY);
    }

    public function removeReadonly(Class_ | Property | Param $node): void
    {
        $isConstructorPromotionBefore = $node instanceof Param && $node->isPromoted();

        $node->flags &= ~Modifiers::READONLY;

        $isConstructorPromotionAfter = $node instanceof Param && $node->isPromoted();

        if ($node instanceof Param && $isConstructorPromotionBefore && ! $isConstructorPromotionAfter) {
            $this->makePublic($node);
        }

        if ($node instanceof Property) {
            $this->publicize($node);
        }
    }

    public function publicize(ClassConst|ClassMethod|Property $node): ClassConst|ClassMethod|Property|null
    {
        // already non-public
        if (! $node->isPublic()) {
            return null;
        }

        // explicitly public
        if ($this->hasVisibility($node, Visibility::PUBLIC)) {
            return null;
        }

        $this->makePublic($node);
        return $node;
    }

    /**
     * This way "abstract", "static", "final" are kept
     */
    private function removeVisibility(ClassMethod | Property | ClassConst | Param $node): void
    {
        // no modifier
        if ($node->flags === 0) {
            return;
        }

        if ($node instanceof Param) {
            $node->flags = 0;
            return;
        }

        if ($node->isPublic()) {
            $node->flags |= Modifiers::PUBLIC;
            $node->flags -= Modifiers::PUBLIC;
        }

        if ($node->isProtected()) {
            $node->flags -= Modifiers::PROTECTED;
        }

        if ($node->isPrivate()) {
            $node->flags -= Modifiers::PRIVATE;
        }
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

    private function replaceVisibilityFlag(ClassMethod | Property | ClassConst | Param $node, int $visibility): void
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
