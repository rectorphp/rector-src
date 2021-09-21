<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PhpParser\Node\Stmt\Class_;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class PhpUpgradeDowngradeRegisteredInSetRule extends AbstractSymplifyRule
{
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
// config/set/php74.php
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// config/set/php74.php
$services->set(TypedDeclatarationRector::class);
CODE_SAMPLE
            ),
        ]);
    }
}