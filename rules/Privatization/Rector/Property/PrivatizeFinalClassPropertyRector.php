<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use Rector\Privatization\Guard\ParentPropertyLookupGuard;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\PrivatizeFinalClassPropertyRectorTest
 */
final class PrivatizeFinalClassPropertyRector extends AbstractRector
{
    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ParentPropertyLookupGuard $parentPropertyLookupGuard,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change property to private if possible', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    protected $value;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private $value;
}
CODE_SAMPLE
            ),
        ]);
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
    public function refactor(Node $node): ?Node
    {
        if (! $node->isFinal()) {
            return null;
        }

        $hasChanged = false;
        $classReflection = null;

        foreach ($node->getProperties() as $property) {
            if ($this->shouldSkipProperty($property)) {
                continue;
            }

            if (! $classReflection instanceof ClassReflection) {
                $classReflection = $this->reflectionResolver->resolveClassReflection($node);
            }

            if (! $this->parentPropertyLookupGuard->isLegal($property, $classReflection)) {
                continue;
            }

            $this->visibilityManipulator->makePrivate($property);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkipProperty(Property $property): bool
    {
        if (count($property->props) !== 1) {
            return true;
        }

        return ! $property->isProtected();
    }
}
