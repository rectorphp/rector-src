<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\CheaperGuardFirstRule\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ExpensiveBeforeCheapGuardRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        throw new \RuntimeException('test stub');
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function refactor(Node $node): ?Node
    {
        $type = $this->getType($node);
        if ($type->isString()->yes()) {
            return null;
        }

        // cheap, independent of $type, but runs after the expensive getType()
        if (! $this->isName($node, 'array')) {
            return null;
        }

        return $node;
    }
}
