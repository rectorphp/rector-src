<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\Double;

use PhpParser\Node;
use PhpParser\Node\Expr\Cast\Double;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

/**
 * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_non-standard_cast_names
 * @see \Rector\Tests\Php85\Rector\Double\DoubleToFloatCastRector\DoubleToFloatCastRectorTest
 */
final class DoubleToFloatCastRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition 
    {
        return new RuleDefinition(
            'Replace deprecated (double) cast with (float)',
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
     * @param Double $node
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
