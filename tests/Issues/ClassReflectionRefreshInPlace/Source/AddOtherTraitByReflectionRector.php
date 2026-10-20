<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ClassReflectionRefreshInPlace\Source;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Reflection\ClassReflection;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\RuleDoc\RuleDefinition;

/**
 * Changes the same class node in place right after AddTraitByReflectionRector did,
 * so it adds the trait again on every pass unless the class reflection is rebuilt for the same node again
 */
final class AddOtherTraitByReflectionRector extends AbstractRector
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
        if (! $classReflection instanceof ClassReflection || $classReflection->hasTraitUse(OtherTrait::class)) {
            return null;
        }

        $node->stmts = array_merge([new TraitUse([new FullyQualified(OtherTrait::class)])], $node->stmts);

        return $node;
    }
}
