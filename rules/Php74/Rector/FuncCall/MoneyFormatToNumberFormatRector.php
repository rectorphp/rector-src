<?php

declare(strict_types=1);

namespace Rector\Php74\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.money-format.php#warning
 *
 * @see \Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\MoneyFormatToNumberFormatRectorTest
 */
final class MoneyFormatToNumberFormatRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_MONEY_FORMAT;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change money_format() to equivalent number_format()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$value = money_format('%i', $value);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$roundedValue = round($value, 2, PHP_ROUND_HALF_ODD);
$value = number_format($roundedValue, 2, '.', '');
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'money_format')) {
            return null;
        }

        return $node;
    }
}
