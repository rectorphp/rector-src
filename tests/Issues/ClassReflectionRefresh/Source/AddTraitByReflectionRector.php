<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ClassReflectionRefresh\Source;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Reflection\ClassReflection;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\RuleDoc\RuleDefinition;

/**
 * Decides on the class reflection only, so it adds the trait again on every pass
 * unless the class reflection reflects the changed class node
 */
final class AddTraitByReflectionRector extends AbstractRector
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
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        // both the scope and the resolved type must see the refreshed class reflection
        $typeClassReflection = $this->getType($node)
            ->getObjectClassReflections()[0] ?? null;

        $hasTraitInScope = $classReflection->hasTraitUse(SomeTrait::class);
        $hasTraitInType = $typeClassReflection instanceof ClassReflection && $typeClassReflection->hasTraitUse(
            SomeTrait::class
        );

        if ($hasTraitInScope && $hasTraitInType) {
            return null;
        }

        $node->stmts = array_merge([new TraitUse([new FullyQualified(SomeTrait::class)])], $node->stmts);

        return $node;
    }
}
