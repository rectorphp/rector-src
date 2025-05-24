<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ChangeSwitchTernary\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AnotherExpressionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    public function refactor(Node $node): ?Node
    {
        // fetch for testing crash no scope after reprint
        ScopeFetcher::fetch($node);

        return null;
    }
}
