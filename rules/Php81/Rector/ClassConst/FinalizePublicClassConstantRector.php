<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://php.watch/versions/8.1/final-class-const
 *
 * @see \Rector\Tests\Php81\Rector\ClassConst\FinalizePublicClassConstantRector\FinalizePublicClassConstantRectorTest
 */
final class FinalizePublicClassConstantRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add final to constants that does not has children', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public const NAME = 'value';
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public final const NAME = 'value';
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
    public function refactor(Node $node): ?Node
    {
        $class = $this->betterNodeFinder->findParentType($node, Class_::class);

        if (! $class instanceof Class_) {
            return null;
        }

        if ($class->isFinal()) {
            return null;
        }

        if ($node->isPrivate()) {
            return null;
        }

        if ($node->isProtected()) {
            return null;
        }

        if ($node->isFinal()) {
            return null;
        }

        if ($this->isClassHasChildren($class)) {
            return null;
        }

        $this->visibilityManipulator->makeFinal($node);
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FINAL_CLASS_CONSTANTS;
    }

    private function isClassHasChildren(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return false;
        }

        $className = (string) $this->nodeNameResolver->getName($class);
        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection) !== [];
    }
}
