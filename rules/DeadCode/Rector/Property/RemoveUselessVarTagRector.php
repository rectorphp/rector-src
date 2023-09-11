<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\RemoveUselessVarTagRectorTest
 */
final class RemoveUselessVarTagRector extends AbstractRector
{
    public function __construct(
        private readonly VarTagRemover $varTagRemover,
        private readonly DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused @var annotation for properties', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var string
     */
    public string $name = 'name';
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public string $name = 'name';
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
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $hasChanged = $this->varTagRemover->removeVarTagIfUseless($phpDocInfo, $node);
        if (! $hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        return $node;
    }
}
