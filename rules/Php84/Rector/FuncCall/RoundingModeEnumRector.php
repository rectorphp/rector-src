<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\FuncCall\RoundingModeEnumRector\RoundingModeEnumRectorTest
 */
final class RoundingModeEnumRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace rounding mode constant to RoundMode enum in round()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
round(1.5, 0, PHP_ROUND_HALF_UP);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
round(1.5, 0, RoundingMode::HalfAwayFromZero);
CODE_SAMPLE
                ,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?FuncCall
    {

        if (! $this->isName($node, 'round')) {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $args = $node->getArgs();

        if (count($args) !== 3) {
            return null;
        }

        $modeArg = $args[2]->value;

        $hasChanged = false;
        if ($modeArg instanceof ConstFetch) {
            $enumCase = match ($modeArg->name->toString()) {
                'PHP_ROUND_HALF_UP' => 'HalfAwayFromZero',
                'PHP_ROUND_HALF_DOWN' => 'HalfTowardsZero',
                'PHP_ROUND_HALF_EVEN' => 'HalfEven',
                'PHP_ROUND_HALF_ODD' => 'HalfOdd',
                default => null,
            };

            if ($enumCase === null) {
                return null;
            }

            $args[2]->value = new ClassConstFetch(new FullyQualified('RoundingMode'), $enumCase);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ROUNDING_MODES;
    }
}
