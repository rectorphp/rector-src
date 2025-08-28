<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\Double;

use PhpParser\Node;
use PhpParser\Node\Expr\Cast\Double;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DoubleToFloatCastRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace (double) cast with (float)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$tmp = (double) $var;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$tmp = (float) $var;
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Double::class];
    }

    /**
     * @param double $node
     */
    public function refactor(Node $node): ?Node
    {
        $kind = $node->getAttribute(AttributeKey::KIND);
        if ($kind !== Double::KIND_DOUBLE) {
            return null;
        }

        $node->setAttribute(AttributeKey::KIND, Double::KIND_FLOAT);
        $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_DOUBLE_CAST;
    }
}
