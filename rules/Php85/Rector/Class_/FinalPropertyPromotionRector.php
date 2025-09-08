<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use Rector\Php85\NodeManipulator\FinalPromotionManipulator;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/final_promotion
 * @see \Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\FinalPropertyPromotionRectorTest
 */
final class FinalPropertyPromotionRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly FinalPromotionManipulator $finalPromotionManipulator,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Promotes constructor properties in final classes', [
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

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {

        return $this->finalPromotionManipulator->process($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FINAL_PROPERTY_PROMOTION;
    }
}
