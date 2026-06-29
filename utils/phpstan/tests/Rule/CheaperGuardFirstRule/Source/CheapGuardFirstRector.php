<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\CheaperGuardFirstRule\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CheapGuardFirstRector extends AbstractRector
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
        // already cheap-first: nothing to report
        if (! $this->isName($node, 'array')) {
            return null;
        }

        $type = $this->getType($node);
        if ($type->isString()->yes()) {
            return null;
        }

        return $node;
    }
}
