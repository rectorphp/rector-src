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
 * Sees the final added by FinalizeByReflectionRector only when the class reflection is refreshed
 * after the class node changed, both on the scope and on the resolved type
 */
final class ReadonlyOnFinalByReflectionRector extends AbstractRector
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
        $scopeClassReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $scopeClassReflection instanceof ClassReflection || ! $scopeClassReflection->isFinal()) {
            return null;
        }

        if ($scopeClassReflection->isReadOnly()) {
            return null;
        }

        $typeClassReflection = $this->getType($node)
            ->getObjectClassReflections()[0] ?? null;
        if (! $typeClassReflection instanceof ClassReflection || ! $typeClassReflection->isFinal()) {
            return null;
        }

        $node->flags |= Modifiers::READONLY;

        return $node;
    }
}
