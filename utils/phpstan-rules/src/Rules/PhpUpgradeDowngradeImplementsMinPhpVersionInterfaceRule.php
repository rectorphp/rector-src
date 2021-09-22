<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\PHPStanRules\Tests\Rules\PhpUpgradeDowngradeImplementsMinPhpVersionInterfaceRule\PhpUpgradeDowngradeImplementsMinPhpVersionInterfaceRule
 */
final class PhpUpgradeDowngradeImplementsMinPhpVersionInterfaceRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Rule %s must implements Rector\VersionBonding\Contract\MinPhpVersionInterface';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        return [];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class ChangeSwitchToMatchRector extends AbstractRector
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

final class ChangeSwitchToMatchRector extends AbstractRector implements MinPhpVersionInterface
{
}
CODE_SAMPLE
            ),
        ]);
    }
}
