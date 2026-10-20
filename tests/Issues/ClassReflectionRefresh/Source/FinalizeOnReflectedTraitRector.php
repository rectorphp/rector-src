<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ClassReflectionRefresh\Source;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\RuleDoc\RuleDefinition;

/**
 * Sees the trait added by AddSomeTraitRector only when the class reflection reflects the changed class node,
 * both on the scope and on the resolved type
 */
final class FinalizeOnReflectedTraitRector extends AbstractRector
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Class_
    {
        if ($node->isFinal()) {
            return null;
        }

        $scopeClassReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $scopeClassReflection instanceof ClassReflection || ! $scopeClassReflection->hasTraitUse(
            SomeTrait::class
        )) {
            return null;
        }

        $typeClassReflection = $this->getType($node)
            ->getObjectClassReflections()[0] ?? null;
        if (! $typeClassReflection instanceof ClassReflection || ! $typeClassReflection->hasTraitUse(
            SomeTrait::class
        )) {
            return null;
        }

        $node->flags |= Modifiers::FINAL;

        return $node;
    }
}
