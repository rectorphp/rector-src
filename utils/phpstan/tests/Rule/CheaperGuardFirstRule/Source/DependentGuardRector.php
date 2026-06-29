<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\CheaperGuardFirstRule\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DependentGuardRector extends AbstractRector
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

        // value-returning guard in between -> reordering is unsafe, must NOT report
        if ($type->isString()->yes()) {
            return $node;
        }

        // depends on $type -> must NOT report
        if ($type->isInteger()->yes()) {
            return null;
        }

        return $node;
    }
}
