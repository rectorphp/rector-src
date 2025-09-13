<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\ValueObject\Visibility;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/final_promotion
 * @see \Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\FinalPropertyPromotionRectorTest
 */
final class FinalPropertyPromotionRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const TAGNAME = 'final';

    public function __construct(
        private VisibilityManipulator $visibilityManipulator,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add native final promoted properties in non-final class to avoid child to override the promoted properties based on `@final` tag', [
            new CodeSample(
                <<<'CODE_SAMPLE'
public function __construct(
    /** @final */
    public string $id
) {}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
public function __construct(
    final public string $id
) {}
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
        if ($node->isFinal()) {
            return null;
        }

        if ($node->isAnonymous()) {
            return null;
        }
        
        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);

        if (! $constructClassMethod instanceof ClassMethod) {
            return null;
        }

        $hasChanged = false;
        $params = $constructClassMethod->getParams();

        foreach ($params as $param) {
            if (! $param->isPromoted()) {
                 continue;
            }

            if ($this->visibilityManipulator->hasVisibility($param, Visibility::FINAL)) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($param);

            if (! $phpDocInfo->hasByName(self::TAGNAME)) {
                continue;
            }
            $hasChanged = true;
            $this->visibilityManipulator->makeFinal($param);
            $phpDocInfo->removeByName(self::TAGNAME);
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($param);
        }
        
        if($hasChanged){
            return $node;
        }
        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FINAL_PROPERTY_PROMOTION;
    }
}
