<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://php.watch/versions/8.1/final-class-const
 *
 * @see \Rector\Tests\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector\RemoveFinalFromConstRectorTest
 */
final class RemoveFinalFromConstRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove final from constants in classes defined as final', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    final public const NAME = 'value';
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public const NAME = 'value';
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
        foreach ($node->getConstants() as $classConst) {
            if (! $classConst->isFinal()) {
                continue;
            }

            $this->visibilityManipulator->removeFinal($classConst);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FINAL_CLASS_CONSTANTS;
    }
}
