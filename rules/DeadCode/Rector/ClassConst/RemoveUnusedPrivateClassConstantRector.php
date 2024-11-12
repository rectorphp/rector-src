<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeManipulator\ClassConstManipulator;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector\RemoveUnusedPrivateClassConstantRectorTest
 */
final class RemoveUnusedPrivateClassConstantRector extends AbstractRector
{
    public function __construct(
        private readonly ClassConstManipulator $classConstManipulator,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused class constants', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    private const SOME_CONST = 'dead';

    public function run()
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
    }
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
        return [ClassConst::class];
    }

    /**
     * @param ClassConst $node
     */
    public function refactor(Node $node): ?int
    {
        if ($this->shouldSkipClassConst($node)) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->classConstManipulator->hasClassConstFetch($node, $classReflection)) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }

    private function shouldSkipClassConst(ClassConst $classConst): bool
    {
        if (! $classConst->isPrivate()) {
            return true;
        }

        if (count($classConst->consts) !== 1) {
            return true;
        }

        $scope = ScopeFetcher::fetch($classConst);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $this->hasParentClassOfEnumSuffix($classReflection);
    }

    private function hasParentClassOfEnumSuffix(ClassReflection $classReflection): bool
    {
        foreach ($classReflection->getParentClassesNames() as $parentClassesName) {
            if (str_ends_with($parentClassesName, 'Enum')) {
                return true;
            }
        }

        return false;
    }
}
