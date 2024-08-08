<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\Issue8752\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class RemoveDoctrineAnnotationValueRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $phpDocUpdater,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('// @todo fill the description', [
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @OA\Schema(
 *     schema="ContentBlockTreeResponseDTO",
 * )
 */
class DoctrineAnnotationWithValue
{
}

?>
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
/**
 * @OA\Schema
 */
class DoctrineAnnotationWithValue
{
}
CODE_SAMPLE,
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
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (!$phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        foreach ($phpDocInfo->getPhpDocNode()->children as $phpDocChildNode) {
            if (!$phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }
            if (!$phpDocChildNode->value instanceof DoctrineAnnotationTagValueNode) {
                continue;
            }

            $doctrineTagValueNode = $phpDocChildNode->value;
            foreach ($doctrineTagValueNode->getValues() as $value) {
                $doctrineTagValueNode->removeValue($value->key);
            }
        }

        $this->phpDocUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }
}
