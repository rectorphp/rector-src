<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\PhpDoc\DeadReturnTagValueNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\RemoveUselessReturnTagRectorTest
 */
final class RemoveUselessReturnTagRector extends AbstractRector
{
    public function __construct(
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly DeadReturnTagValueNodeAnalyzer $deadReturnTagValueNodeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove @return docblock with same type as defined in PHP',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use stdClass;

class SomeClass
{
    /**
     * @return stdClass
     */
    public function foo(): stdClass
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use stdClass;

class SomeClass
{
    public function foo(): stdClass
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        // remove existing type
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();
        if (! $returnTagValueNode instanceof ReturnTagValueNode) {
            return null;
        }

        $isReturnTagValueDead = $this->deadReturnTagValueNodeAnalyzer->isDead($returnTagValueNode, $node);
        if (! $isReturnTagValueDead) {
            return null;
        }

        $phpDocInfo->removeByType(ReturnTagValueNode::class);
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }
}
